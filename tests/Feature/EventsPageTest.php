<?php

it('renders the events page successfully', function () {
    $view = view('events.index', ['events' => collect([]), 'casualGames' => collect([])]);
    $html = $view->render();

    expect($html)->toContain('Events')
        ->and($html)->toContain('Casual Matches');
});
