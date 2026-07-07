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
            'set_scores' => ['required', 'array', 'min:1', 'max:3'],
            'set_scores.*.team1_score' => ['required', 'integer', 'min:0'],
            'set_scores.*.team2_score' => ['required', 'integer', 'min:0'],
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

                $teamOneSetsWon = 0;
                $teamTwoSetsWon = 0;

                foreach ($this->setScores() as $index => $setScore) {
                    if ($setScore['team1_score'] === $setScore['team2_score']) {
                        $validator->errors()->add("set_scores.{$index}.team1_score", 'Set scores cannot be tied.');

                        continue;
                    }

                    if ($setScore['team1_score'] > $setScore['team2_score']) {
                        $teamOneSetsWon++;
                    } else {
                        $teamTwoSetsWon++;
                    }
                }

                if (! $validator->errors()->any() && $teamOneSetsWon === $teamTwoSetsWon) {
                    $validator->errors()->add('set_scores', 'Final scores must produce a winning side.');
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

    private function game(): ?Game
    {
        $game = $this->route('game');

        return $game instanceof Game ? $game : null;
    }
}
