<?php

namespace App\Http\Requests;

use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreGameRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'team_one_players' => $this->filledPlayerIds('team_one_players'),
            'team_two_players' => $this->filledPlayerIds('team_two_players'),
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $event = $this->event();

        return $event !== null
            && (int) $event->organizer_id === (int) $this->user()?->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'format' => ['required', Rule::in(['singles', 'doubles'])],
            'scheduled_at' => ['required', 'date'],
            'team_one_players' => ['required', 'array', 'min:1', 'max:2'],
            'team_one_players.*' => ['required', 'integer', 'distinct', Rule::exists('users', 'id')],
            'team_two_players' => ['required', 'array', 'min:1', 'max:2'],
            'team_two_players.*' => ['required', 'integer', 'distinct', Rule::exists('users', 'id')],
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

                $event = $this->event();

                if ($event === null) {
                    $validator->errors()->add('event', 'The selected event could not be found.');

                    return;
                }

                $playersPerTeam = $this->input('format') === 'doubles' ? 2 : 1;
                $teamOnePlayers = $this->playerIds('team_one_players');
                $teamTwoPlayers = $this->playerIds('team_two_players');
                $allPlayers = array_merge($teamOnePlayers, $teamTwoPlayers);

                if (count($teamOnePlayers) !== $playersPerTeam) {
                    $validator->errors()->add('team_one_players', "Team 1 must have {$playersPerTeam} player(s) for this format.");
                }

                if (count($teamTwoPlayers) !== $playersPerTeam) {
                    $validator->errors()->add('team_two_players', "Team 2 must have {$playersPerTeam} player(s) for this format.");
                }

                if (count(array_unique($allPlayers)) !== count($allPlayers)) {
                    $validator->errors()->add('team_two_players', 'A player can only be assigned to one side of a game.');
                }

                $approvedPlayerIds = $event->approvedPlayers()
                    ->whereKey($allPlayers)
                    ->pluck((new User)->qualifyColumn('id'))
                    ->map(fn ($id) => (int) $id)
                    ->all();

                if (array_diff($allPlayers, $approvedPlayerIds) !== []) {
                    $validator->errors()->add('team_one_players', 'All assigned players must be approved for this event.');
                }
            },
        ];
    }

    /**
     * @return array<int, int>
     */
    public function teamOnePlayers(): array
    {
        return $this->playerIds('team_one_players');
    }

    /**
     * @return array<int, int>
     */
    public function teamTwoPlayers(): array
    {
        return $this->playerIds('team_two_players');
    }

    private function event(): ?Event
    {
        $event = $this->route('event');

        return $event instanceof Event ? $event : null;
    }

    /**
     * @return array<int, int>
     */
    private function playerIds(string $key): array
    {
        return array_values(array_map('intval', (array) $this->input($key, [])));
    }

    /**
     * @return array<int, mixed>
     */
    private function filledPlayerIds(string $key): array
    {
        return array_values(array_filter(
            (array) $this->input($key, []),
            fn ($playerId) => $playerId !== null && $playerId !== ''
        ));
    }
}
