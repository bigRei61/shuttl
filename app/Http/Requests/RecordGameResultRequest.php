<?php

namespace App\Http\Requests;

use App\Models\Game;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RecordGameResultRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'set_scores' => collect($this->input('set_scores', []))
                ->filter(fn ($setScore) => is_array($setScore) && (($setScore['team1_score'] ?? '') !== '' || ($setScore['team2_score'] ?? '') !== ''))
                ->values()
                ->all(),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $game = $this->game();
        $game?->loadMissing('event');

        return $game !== null
            && (int) $game->event?->organizer_id === (int) $this->user()?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'set_scores' => ['required', 'array'],
            'set_scores.*.team1_score' => ['nullable'],
            'set_scores.*.team2_score' => ['nullable'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'set_scores.required' => 'Match must have 1 to 3 completed sets.',
            'set_scores.array' => 'Unrecognized scoring error — check set scores and try again.',
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->any()) {
                    return;
                }

                $setScores = $this->rawSetScores();

                if ($setScores === []) {
                    $validator->errors()->add('set_scores', 'Match must have 1 to 3 completed sets.');

                    return;
                }

                $teamOneSetsWon = 0;
                $teamTwoSetsWon = 0;
                $hasMissingScore = false;
                $setWinners = [];

                foreach ($setScores as $index => $setScore) {
                    if (! $this->hasBothScores($setScore)) {
                        $hasMissingScore = true;

                        continue;
                    }

                    $setError = $this->setScoreError($setScore['team1_score'], $setScore['team2_score']);

                    if ($setError !== null) {
                        $validator->errors()->add("set_scores.{$index}.team1_score", $setError);

                        return;
                    }

                    $teamOneScore = (int) $setScore['team1_score'];
                    $teamTwoScore = (int) $setScore['team2_score'];

                    if ($teamOneScore > $teamTwoScore) {
                        $teamOneSetsWon++;
                        $setWinners[] = 1;
                    } else {
                        $teamTwoSetsWon++;
                        $setWinners[] = 2;
                    }
                }

                if ($hasMissingScore) {
                    $validator->errors()->add('set_scores', 'Missing one or more set scores.');

                    return;
                }

                $matchError = $this->matchScoreError($setWinners, $teamOneSetsWon, $teamTwoSetsWon);

                if ($matchError !== null) {
                    $validator->errors()->add('set_scores', $matchError);
                }
            },
        ];
    }

    /**
     * @return array<int, array{team1_score: int, team2_score: int}>
     */
    public function setScores(): array
    {
        return collect($this->input('set_scores', []))
            ->map(fn (array $setScore) => [
                'team1_score' => (int) $setScore['team1_score'],
                'team2_score' => (int) $setScore['team2_score'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{team1_score: mixed, team2_score: mixed}>
     */
    private function rawSetScores(): array
    {
        return collect($this->input('set_scores', []))
            ->map(fn (array $setScore) => [
                'team1_score' => $setScore['team1_score'] ?? null,
                'team2_score' => $setScore['team2_score'] ?? null,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array{team1_score: mixed, team2_score: mixed}  $setScore
     */
    private function hasBothScores(array $setScore): bool
    {
        return $setScore['team1_score'] !== null
            && $setScore['team1_score'] !== ''
            && $setScore['team2_score'] !== null
            && $setScore['team2_score'] !== '';
    }

    private function setScoreError(mixed $teamOneScore, mixed $teamTwoScore): ?string
    {
        $scores = [$teamOneScore, $teamTwoScore];

        foreach ($scores as $score) {
            if (is_numeric($score) && (float) $score < 0) {
                return 'Scores cannot be negative.';
            }
        }

        foreach ($scores as $score) {
            if (is_numeric($score) && (float) $score > 30) {
                return 'No set score can exceed 30.';
            }
        }

        foreach ($scores as $score) {
            if (filter_var($score, FILTER_VALIDATE_INT) === false) {
                return 'Scores must be whole numbers.';
            }
        }

        $teamOneScore = (int) $teamOneScore;
        $teamTwoScore = (int) $teamTwoScore;

        if ($teamOneScore === $teamTwoScore) {
            return 'A set cannot end in a tie.';
        }

        $winnerScore = max($teamOneScore, $teamTwoScore);
        $loserScore = min($teamOneScore, $teamTwoScore);
        $margin = $winnerScore - $loserScore;

        if ($loserScore >= $winnerScore) {
            return "Loser's score cannot equal or exceed winner's.";
        }

        if ($winnerScore < 21) {
            return 'Winning score must be at least 21.';
        }

        if ($margin < 2 && $winnerScore !== 30) {
            return 'Winner must lead by at least 2 points.';
        }

        if ($winnerScore >= 22 && $winnerScore <= 29 && $margin !== 2) {
            return 'Margin must be exactly 2 between 22–29.';
        }

        if ($winnerScore === 30 && $loserScore !== 29) {
            return 'At 30 points, loser must be exactly 29.';
        }

        if ($teamOneScore === 29 && $teamTwoScore === 29) {
            return 'Once at 29-29, next point wins — no further extension.';
        }

        if (! $this->isValidFinalSetScore($winnerScore, $loserScore)) {
            return 'Set is still in progress, not completed.';
        }

        return null;
    }

    private function isValidFinalSetScore(int $winnerScore, int $loserScore): bool
    {
        if ($winnerScore < 0 || $loserScore < 0) {
            return false;
        }

        if ($winnerScore > 30 || $loserScore > 30) {
            return false;
        }

        if ($loserScore >= $winnerScore) {
            return false;
        }

        $margin = $winnerScore - $loserScore;

        if ($winnerScore === 30) {
            return $loserScore === 29;
        }

        if ($winnerScore > 21) {
            return $margin === 2;
        }

        if ($winnerScore === 21) {
            return $margin >= 2 && $loserScore <= 19;
        }

        return false;
    }

    /**
     * @param  array<int, int>  $setWinners
     */
    private function matchScoreError(array $setWinners, int $teamOneSetsWon, int $teamTwoSetsWon): ?string
    {
        $completedSetCount = count($setWinners);

        if ($completedSetCount < 1) {
            return 'Match must have 1 to 3 completed sets.';
        }

        if ($completedSetCount > 3) {
            return 'Cannot exceed 3 sets in a best-of-3 match.';
        }

        $runningTeamOneWins = 0;
        $runningTeamTwoWins = 0;

        foreach ($setWinners as $index => $setWinner) {
            if ($setWinner === 1) {
                $runningTeamOneWins++;
            } else {
                $runningTeamTwoWins++;
            }

            if ($index < $completedSetCount - 1 && ($runningTeamOneWins === 2 || $runningTeamTwoWins === 2)) {
                return 'Match must end once a side wins 2 sets.';
            }
        }

        if ($completedSetCount === 1) {
            return "Match can't end 1-0 — third set required unless 2 sets already won.";
        }

        if ($teamOneSetsWon + $teamTwoSetsWon !== $completedSetCount) {
            return "Set win totals don't add up correctly.";
        }

        if (max($teamOneSetsWon, $teamTwoSetsWon) < 2) {
            return 'Winner must have won at least 2 sets.';
        }

        if (! in_array([$teamOneSetsWon, $teamTwoSetsWon], [
            [2, 0],
            [2, 1],
            [1, 2],
            [0, 2],
        ], true)) {
            return 'Unrecognized scoring error — check set scores and try again.';
        }

        return null;
    }

    private function game(): ?Game
    {
        $game = $this->route('game');

        return $game instanceof Game ? $game : null;
    }
}
