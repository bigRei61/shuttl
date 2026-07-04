<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tournaments - Shuttl</title>

	<link href="{{ asset('landing/img/favicon.ico') }}" rel="shortcut icon"/>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700,700i" rel="stylesheet">

	<link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}"/>
	<link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}"/>
	<link rel="stylesheet" href="{{ asset('landing/css/style.css') }}"/>
	<link rel="stylesheet" href="{{ asset('landing/css/animate.css') }}"/>

	<!-- Tournament page styles (extends style.css, same color system) -->
	<style>
		.tournament-list-section {
			background: #f5f7fa;
		}

		/* ---- Section head ---- */
		.tp-section-head {
			display: flex;
			align-items: flex-end;
			justify-content: space-between;
			flex-wrap: wrap;
			margin-bottom: 45px;
		}

		.tp-section-head h2 { margin: 0; }
		.tp-section-head p { margin: 8px 0 0; }

		/* ---- Tournament card: image on top, content stacked below ---- */
		.tournament-item.tp-card {
			cursor: pointer;
			background: #fff;
			border: 1px solid #eaedf2;
			border-radius: 14px;
			overflow: hidden;
			transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
		}

		.tournament-item.tp-card:hover {
			transform: translateY(-6px);
			border-color: #dfe4ea;
			box-shadow: 0 20px 40px rgba(19,19,19,.08);
		}

		.tournament-item .ti-thumb {
			position: relative;
			width: 100%;
			height: 260px;
			float: none;
			margin: 0;
		}

		.ti-featured {
			position: absolute;
			top: 16px;
			left: 16px;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			padding: 7px 16px;
			font-size: 11px;
			font-weight: 700;
			letter-spacing: .3px;
			text-transform: uppercase;
			color: #0f9c8c;
			background: #d8f6ee;
			border-radius: 50px;
		}

		.tournament-item .ti-content { padding: 26px 26px 22px; }

		.ti-type {
			display: inline-block;
			padding: 6px 18px;
			font-size: 10px;
			font-weight: 700;
			letter-spacing: .3px;
			text-transform: uppercase;
			color: #0f9c8c;
			background: #d8f6ee;
			border-radius: 50px;
		}

		.tournament-item .ti-text {
			padding-left: 0;
			padding-top: 0;
		}

		.tournament-item .ti-text h4 {
			color: #131313;
			font-size: 22px;
			margin-bottom: 18px;
			line-height: 1.35;
		}

		.tournament-item .ti-content .ti-meta {
			list-style: none;
			margin-bottom: 0;
		}

		.tournament-item .ti-content .ti-meta li {
			font-size: 13px;
			color: #6b7280;
			margin-bottom: 9px;
		}

		.tournament-item .ti-content .ti-meta li strong {
			display: inline-block;
			min-width: 68px;
			font-weight: 700;
			color: #131313;
		}

		.tournament-item .ti-content .ti-caption {
			margin-top: 18px;
			padding-top: 16px;
			border-top: 1px solid #eef0f3;
			font-size: 13px;
			color: #9a9a9a;
		}

		/* ---- Detail view ---- */
		.tournament-detail-section { display: none; }
		.tournament-detail-section.tp-active { display: block; }

		.tp-back-link {
			display: inline-flex;
			align-items: center;
			font-size: 13px;
			font-weight: 500;
			color: #737373;
			margin-bottom: 30px;
			cursor: pointer;
		}

		.tp-back-link i { margin-right: 8px; color: #4EDFCE; }
		.tp-back-link:hover { color: #131313; }

		.tp-detail-head {
			background: #fff;
			border: 1px solid #eaedf2;
			border-radius: 16px;
			padding: 40px;
			margin-bottom: 45px;
			position: relative;
			overflow: hidden;
		}

		.tp-detail-head .tp-detail-bg { display: none; }

		.tp-detail-head .ti-notic { width: auto; margin-bottom: 22px; }

		.tp-detail-head h3 {
			color: #131313;
			margin-bottom: 18px;
			position: relative;
			z-index: 2;
		}

		.tp-detail-meta {
			list-style: none;
			display: flex;
			flex-wrap: wrap;
			gap: 30px 50px;
			position: relative;
			z-index: 2;
		}

		.tp-detail-meta li { font-size: 12px; color: #9a9a9a; }

		.tp-detail-meta li span {
			display: block;
			margin-top: 6px;
			font-size: 15px;
			font-weight: 500;
			color: #131313;
		}

		.tp-detail-meta li span.tp-accent { color: #0f9c8c; }

		.tp-content-grid {
			display: grid;
			grid-template-columns: minmax(0, 1fr) minmax(260px, 320px);
			gap: 24px;
			align-items: start;
		}

		.tp-player-panel {
			background: #fff;
			border: 1px solid #eaedf2;
			border-radius: 16px;
			padding: 24px 28px;
			position: sticky;
			top: 20px;
		}

		.tp-player-panel-head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 12px;
			margin-bottom: 16px;
		}

		.tp-player-panel-head h4 {
			margin: 0;
			font-size: 18px;
			color: #131313;
		}

		.tp-player-panel-head p {
			margin: 0;
			font-size: 13px;
			color: #9a9a9a;
		}

		.tp-player-list {
			list-style: none;
			margin: 0;
			padding: 0;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.tp-player-list li {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 10px;
			padding: 10px 12px;
			background: #f8fafc;
			border: 1px solid #eef2f6;
			border-radius: 10px;
			font-size: 13px;
			color: #4b5563;
		}

		.tp-player-list li strong {
			font-size: 12px;
			color: #131313;
		}

		/* ---- Filter tabs ---- */
		.tp-filter-tabs {
			list-style: none;
			display: flex;
			flex-wrap: wrap;
			gap: 12px;
			margin-bottom: 35px;
		}

		.tp-filter-tabs li a {
			display: inline-block;
			padding: 9px 24px;
			font-size: 12px;
			font-weight: 500;
			text-transform: uppercase;
			color: #131313;
			background: #eef2f6;
			border-radius: 50px;
			transition: background .2s ease, color .2s ease;
		}

		.tp-filter-tabs li a:hover { color: #131313; background: #dbe2ec; }
		.tp-filter-tabs li a.tp-active { color: #131313; background: #4EDFCE; }

		/* ---- Game row list ---- */
		.tp-game-list { list-style: none; }

		.game-row {
			display: flex;
			align-items: center;
			flex-wrap: wrap;
			gap: 18px 28px;
			width: min(100%, 960px);
			margin: 0 auto 18px;
			background: #fff;
			border: 1px solid #eaedf2;
			border-left: 4px solid #4EDFCE;
			border-radius: 12px;
			padding: 24px 28px;
			transition: box-shadow .2s ease, border-color .2s ease;
		}

		.game-row:hover { box-shadow: 0 10px 30px rgba(19,19,19,.08); }
		.game-row.type-doubles { border-left-color: #694eae; }

		.game-row .gr-when { min-width: 108px; }

		.game-row .gr-when .gr-date {
			font-size: 12px;
			font-weight: 500;
			color: #131313;
			text-transform: uppercase;
		}

		.game-row .gr-when .gr-time {
			display: block;
			margin-top: 4px;
			font-size: 20px;
			font-weight: 500;
			color: #4EDFCE;
		}

		.game-row .gr-divider {
			width: 1px;
			align-self: stretch;
			background: #ececec;
		}

		.game-row .gr-info { flex: 1 1 280px; min-width: 220px; }

		.game-row .gr-stage {
			display: inline-block;
			margin-bottom: 8px;
			font-size: 11px;
			font-weight: 500;
			text-transform: uppercase;
			letter-spacing: .5px;
			color: #9a9a9a;
		}

		.game-row .gr-players {
			font-size: 15px;
			font-weight: 500;
			color: #131313;
		}

		.game-row .gr-players .gr-vs {
			display: inline-block;
			margin: 0 10px;
			font-size: 12px;
			font-weight: 400;
			color: #b5b5b5;
			text-transform: uppercase;
		}

		.game-row .gr-side {
			flex: 0 0 240px;
			margin-left: auto;
			padding-left: 8px;
			border-left: 1px solid #ececec;
		}

		.game-row .gr-side-title {
			font-size: 11px;
			font-weight: 700;
			letter-spacing: .5px;
			text-transform: uppercase;
			color: #9a9a9a;
			margin-bottom: 10px;
		}

		.game-row .gr-player-list {
			list-style: none;
			margin: 0;
			padding: 0;
			display: flex;
			flex-direction: column;
			gap: 8px;
		}

		.game-row .gr-player-list li {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 10px;
			font-size: 13px;
			color: #4b5563;
		}

		.game-row .gr-player-list li strong {
			font-size: 12px;
			color: #131313;
		}

		.game-row .gr-tags {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-left: auto;
		}

		.gr-badge {
			display: inline-block;
			padding: 6px 16px;
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			border-radius: 50px;
		}

		.gr-badge.format-singles { background: #e8faf5; color: #0f9c8c; }
		.gr-badge.format-doubles { background: #f0eafa; color: #694eae; }

		.gr-badge.status-upcoming { background: #f2f4f7; color: #6b7280; }
		.gr-badge.status-live { background: #ffe3ec; color: #ff205f; }
		.gr-badge.status-completed { background: #e8f7ea; color: #2f9e4f; }

		.tp-empty-state {
			text-align: center;
			padding: 60px 0;
			color: #878787;
			display: none;
		}

		.tp-empty-state.tp-active { display: block; }

		@media (max-width: 991px) {
			.tp-content-grid {
				grid-template-columns: 1fr;
			}

			.tp-player-panel {
				position: static;
			}
		}

		@media (max-width: 767px) {
			.tp-detail-head { padding: 28px; }
			.tp-detail-meta { gap: 20px 30px; }
			.game-row { padding: 20px; }
			.game-row .gr-divider { display: none; }
			.game-row .gr-side {
				flex-basis: 100%;
				width: 100%;
				margin-left: 0;
				padding-left: 0;
				padding-top: 14px;
				border-left: 0;
				border-top: 1px solid #ececec;
			}
			.game-row .gr-tags { margin-left: 0; }
		}
	</style>
</head>
<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>
	@include('partials.header')

	<!-- Page info section -->
	<section class="page-info-section set-bg" data-setbg="{{ asset('page-top-bg/5.png') }}">
		<div class="pi-content">
			<div class="container">
				<div class="row">
					<div class="col-xl-6 col-lg-7 text-white">
						<h2>Tournaments</h2>
						<p>Browse every tournament running on Shuttl, then tap one to see the full match schedule &mdash; dates, players, and whether it's singles or doubles.</p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Page info section end -->


	<!-- Tournament list section -->
	<section class="tournament-list-section page-section spad" id="tournament-list-view">
		<div class="container">
			<div class="tp-section-head">
				<div>
					<h2>All Tournaments</h2>
					<p>Tap a tournament card to view its games.</p>
				</div>
			</div>

			<div class="row" id="tournament-cards">

				<!-- Tournament card 1 -->
				<div class="col-lg-4 col-md-6 mb-4">
					<div class="tournament-item tp-card h-100" data-tournament="mtdy2026">
						<div class="ti-thumb set-bg" data-setbg="{{ asset('landing/img/1.jpg') }}">
							<span class="ti-featured"><i class="fa fa-star"></i> Featured</span>
						</div>
						<div class="ti-content">
							<div class="ti-text">
								<h4>CASAFI</h4>
								<ul class="ti-meta">
									<li><strong>Type:</strong> Tournament</li>
									<li><strong>Starts:</strong> Jul 20, 2026</li>
									<li><strong>Ends:</strong> Jul 21, 2026</li>
									<li><strong>Location:</strong> MTDY Badminton Court</li>
									<li><strong>Organizer:</strong> Admin User</li>
									<li><strong>Slots:</strong> 10 teams</li>
								</ul>
								<p class="ti-caption">Interschool Competition</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Tournament card 2 -->
				<div class="col-lg-4 col-md-6 mb-4">
					<div class="tournament-item tp-card h-100" data-tournament="cebusmash">
						<div class="ti-thumb set-bg" data-setbg="{{ asset('landing/img/2.jpg') }}">
							<span class="ti-featured"><i class="fa fa-star"></i> Featured</span>
						</div>
						<div class="ti-content">
							<div class="ti-text">
								<h4>Cebu City Weekend Smash</h4>
								<ul class="ti-meta">
									<li><strong>Type:</strong> Community Event</li>
									<li><strong>Starts:</strong> Jul 01, 2026</li>
									<li><strong>Ends:</strong> Jul 05, 2026</li>
									<li><strong>Location:</strong> Cebu Sports Hub</li>
									<li><strong>Organizer:</strong> Admin User</li>
									<li><strong>Slots:</strong> 16 players</li>
								</ul>
								<p class="ti-caption">Open Community Meetup</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Tournament card 3 -->
				<div class="col-lg-4 col-md-6 mb-4">
					<div class="tournament-item tp-card h-100" data-tournament="hoopswinter">
						<div class="ti-thumb set-bg" data-setbg="{{ asset('landing/img/3.jpg') }}">
							<span class="ti-featured"><i class="fa fa-star"></i> Featured</span>
						</div>
						<div class="ti-content">
							<div class="ti-text">
								<h4>Hoops and Rackets Winter Cup</h4>
								<ul class="ti-meta">
									<li><strong>Type:</strong> Tournament</li>
									<li><strong>Starts:</strong> Dec 14, 2026</li>
									<li><strong>Ends:</strong> Dec 16, 2026</li>
									<li><strong>Location:</strong> Hoops and Rackets Badminton Court</li>
									<li><strong>Organizer:</strong> Admin User</li>
									<li><strong>Slots:</strong> 10 teams</li>
								</ul>
								<p class="ti-caption">Winter Championship Series</p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- Tournament detail section -->
	<section class="tournament-detail-section page-section spad" id="tournament-detail-view">
		<div class="container">

			<span class="tp-back-link" id="tp-back-link"><i class="fa fa-long-arrow-left"></i> Back to all tournaments</span>

			<div class="tp-detail-head">
				<div class="ti-notic"><span class="ti-type" id="tp-detail-badge">Premium Tournament</span></div>
				<h3 id="tp-detail-title">Tournament name</h3>
				<ul class="tp-detail-meta">
					<li>Dates <span class="tp-accent" id="tp-detail-dates">-</span></li>
					<li>Participants <span id="tp-detail-participants">-</span></li>
					<li>Total Games <span id="tp-detail-count">-</span></li>
				</ul>
			</div>

			<div class="tp-content-grid">
				<div>
					<ul class="tp-filter-tabs" id="tp-filter-tabs">
						<li><a href="#" class="tp-active" data-filter="all">All Games</a></li>
						<li><a href="#" data-filter="singles">Singles</a></li>
						<li><a href="#" data-filter="doubles">Doubles</a></li>
					</ul>

					<ul class="tp-game-list" id="tp-game-list">
						<!-- game rows injected by JS -->
					</ul>

					<div class="tp-empty-state" id="tp-empty-state">
						<p>No games match this filter yet.</p>
					</div>
				</div>

				<div class="tp-player-panel">
					<div class="tp-player-panel-head">
						<h4>Players & Ratings</h4>
						<p>Overall tournament roster</p>
					</div>
					<ul class="tp-player-list" id="tp-player-list"></ul>
				</div>
			</div>

		</div>
	</section>
	<!-- Tournament detail section end -->

	<!-- Tournament data below is placeholder/demo data. Replace with real data from
	     EventController / GameController once tournaments live in the database. -->

	<script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('landing/js/main.js') }}"></script>

	<!-- Tournament page data + interactions -->
	<script>
	(function () {

		/* ----------------------------------------------------------
		   Replace this object with a fetch() to your app's API.
		   Each tournament key matches the data-tournament attribute
		   on the cards above.
		---------------------------------------------------------- */
		var tournamentData = {
			mtdy2026: {
				badge: "Premium Tournament",
				title: "MTDY 2026 Championships",
				dates: "Jun 20 - Jul 01, 2026",
				participants: "10 teams",
				playerRatings: [{ name: "J. Mangubat", rating: 88 }, { name: "R. Reilly", rating: 84 }, { name: "A. Cruz", rating: 82 }, { name: "M. Torres", rating: 79 }, { name: "S. Gudmalin", rating: 86 }, { name: "L. Bautista", rating: 80 }, { name: "D. Santos", rating: 81 }, { name: "K. Villar", rating: 78 }, { name: "N. Aquino", rating: 83 }],
				games: [
					{ stage: "Quarterfinal", date: "Jun 21, 2026", time: "9:00 AM", format: "doubles", status: "completed", players: ["J. Mangubat", "R. Reilly"], teamB: ["A. Cruz", "M. Torres"] },
					{ stage: "Quarterfinal", date: "Jun 21, 2026", time: "11:00 AM", format: "singles", status: "completed", players: ["S. Gudmalin"], teamB: ["L. Bautista"] },
					{ stage: "Semifinal", date: "Jun 25, 2026", time: "2:00 PM", format: "doubles", status: "completed", players: ["J. Mangubat", "R. Reilly"], teamB: ["D. Santos", "K. Villar"] },
					{ stage: "Semifinal", date: "Jun 25, 2026", time: "4:00 PM", format: "singles", status: "completed", players: ["S. Gudmalin"], teamB: ["N. Aquino"] },
					{ stage: "Final", date: "Jul 01, 2026", time: "6:00 PM", format: "doubles", status: "completed", players: ["J. Mangubat", "R. Reilly"], teamB: ["S. Gudmalin", "N. Aquino"] }
				]
			},
			cebusmash: {
				badge: "Community Tournament",
				title: "Cebu City Weekend Smash",
				dates: "Jul 01 - Jul 05, 2026",
				participants: "16 players",
				playerRatings: [{ name: "M. Uy", rating: 84 }, { name: "P. Alcoseba", rating: 81 }, { name: "C. Espina", rating: 78 }, { name: "R. Dagoc", rating: 80 }, { name: "F. Yap", rating: 77 }, { name: "J. Tan", rating: 79 }, { name: "G. Ababon", rating: 82 }, { name: "V. Suarez", rating: 83 }, { name: "W. Chiong", rating: 76 }, { name: "B. Ouano", rating: 75 }],
				games: [
					{ stage: "Round 1", date: "Jul 02, 2026", time: "5:00 PM", format: "singles", status: "live", players: ["M. Uy"], teamB: ["P. Alcoseba"] },
					{ stage: "Round 1", date: "Jul 02, 2026", time: "6:00 PM", format: "doubles", status: "upcoming", players: ["C. Espina", "R. Dagoc"], teamB: ["F. Yap", "J. Tan"] },
					{ stage: "Round 1", date: "Jul 03, 2026", time: "5:00 PM", format: "singles", status: "upcoming", players: ["G. Ababon"], teamB: ["V. Suarez"] },
					{ stage: "Round 2", date: "Jul 04, 2026", time: "7:00 PM", format: "doubles", status: "upcoming", players: ["C. Espina", "R. Dagoc"], teamB: ["W. Chiong", "B. Ouano"] },
					{ stage: "Final", date: "Jul 05, 2026", time: "6:00 PM", format: "singles", status: "upcoming", players: ["TBD"], teamB: ["TBD"] }
				]
			},
			hoopswinter: {
				badge: "Premium Tournament",
				title: "Hoops and Rackets Winter Cup",
				dates: "Dec 14 - Dec 16, 2026",
				participants: "10 teams",
				playerRatings: [{ name: "Team Falcon", rating: 85 }, { name: "Team Hornet", rating: 82 }, { name: "I. Cortes", rating: 84 }, { name: "H. Delos Reyes", rating: 83 }, { name: "Team Osprey", rating: 81 }],
				games: [
					{ stage: "Quarterfinal", date: "Dec 14, 2026", time: "9:00 AM", format: "doubles", status: "upcoming", players: ["Team Falcon"], teamB: ["Team Hornet"] },
					{ stage: "Quarterfinal", date: "Dec 14, 2026", time: "11:00 AM", format: "singles", status: "upcoming", players: ["I. Cortes"], teamB: ["H. Delos Reyes"] },
					{ stage: "Semifinal", date: "Dec 15, 2026", time: "2:00 PM", format: "doubles", status: "upcoming", players: ["Team Falcon"], teamB: ["Team Osprey"] },
					{ stage: "Final", date: "Dec 16, 2026", time: "6:00 PM", format: "singles", status: "upcoming", players: ["TBD"], teamB: ["TBD"] }
				]
			}
		};

		var listView = document.getElementById("tournament-list-view");
		var detailView = document.getElementById("tournament-detail-view");
		var currentFilter = "all";
		var activeTournament = null;

		function escapeHtml(str) {
			return String(str).replace(/[&<>"']/g, function (c) {
				return { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c];
			});
		}

		function statusLabel(status) {
			if (status === "live") return "Live";
			if (status === "completed") return "Completed";
			return "Upcoming";
		}

		function renderGames() {
			var data = tournamentData[activeTournament];
			var list = document.getElementById("tp-game-list");
			var empty = document.getElementById("tp-empty-state");
			list.innerHTML = "";

			var games = data.games.filter(function (g) {
				return currentFilter === "all" || g.format === currentFilter;
			});

			if (games.length === 0) {
				empty.classList.add("tp-active");
				return;
			}
			empty.classList.remove("tp-active");

			games.forEach(function (g) {
				var isDoubles = g.format === "doubles";
				var li = document.createElement("li");
				li.className = "game-row " + (isDoubles ? "type-doubles" : "type-singles");
				li.innerHTML =
					'<div class="gr-when">' +
						'<div class="gr-date">' + escapeHtml(g.date) + '</div>' +
						'<span class="gr-time">' + escapeHtml(g.time) + '</span>' +
					'</div>' +
					'<div class="gr-divider"></div>' +
					'<div class="gr-info">' +
						'<span class="gr-stage">' + escapeHtml(g.stage) + '</span>' +
						'<div class="gr-players">' +
							escapeHtml(g.players.join(" & ")) +
							'<span class="gr-vs">vs</span>' +
							escapeHtml(g.teamB.join(" & ")) +
						'</div>' +
					'</div>' +
					'<div class="gr-tags">' +
						'<span class="gr-badge format-' + g.format + '">' + (isDoubles ? "Doubles" : "Singles") + '</span>' +
						'<span class="gr-badge status-' + g.status + '">' + statusLabel(g.status) + '</span>' +
					'</div>';
				list.appendChild(li);
			});
		}

		function renderPlayersList(data) {
			var list = document.getElementById("tp-player-list");
			if (!list) return;

			var players = (data.playerRatings || []).map(function (player) {
				return '<li><span>' + escapeHtml(player.name) + '</span><strong>' + escapeHtml(player.rating) + '</strong></li>';
			}).join("");

			list.innerHTML = players || '<li><span>No player ratings available</span><strong>-</strong></li>';
		}

		function openTournament(key) {
			var data = tournamentData[key];
			if (!data) return;
			activeTournament = key;
			currentFilter = "all";

			document.getElementById("tp-detail-badge").textContent = data.badge;
			document.getElementById("tp-detail-title").textContent = data.title;
			document.getElementById("tp-detail-dates").textContent = data.dates;
			document.getElementById("tp-detail-participants").textContent = data.participants;
			document.getElementById("tp-detail-count").textContent = data.games.length;
			renderPlayersList(data);

			var tabs = document.querySelectorAll("#tp-filter-tabs a");
			tabs.forEach(function (t) {
				t.classList.toggle("tp-active", t.getAttribute("data-filter") === "all");
			});

			renderGames();

			listView.style.display = "none";
			detailView.classList.add("tp-active");
			window.scrollTo({ top: 0, behavior: "smooth" });
		}

		function closeTournament() {
			detailView.classList.remove("tp-active");
			listView.style.display = "";
			window.scrollTo({ top: 0, behavior: "smooth" });
		}

		document.querySelectorAll(".tp-card").forEach(function (card) {
			card.addEventListener("click", function () {
				openTournament(card.getAttribute("data-tournament"));
			});
		});

		document.getElementById("tp-back-link").addEventListener("click", closeTournament);

		document.querySelectorAll("#tp-filter-tabs a").forEach(function (tab) {
			tab.addEventListener("click", function (e) {
				e.preventDefault();
				currentFilter = tab.getAttribute("data-filter");
				document.querySelectorAll("#tp-filter-tabs a").forEach(function (t) {
					t.classList.remove("tp-active");
				});
				tab.classList.add("tp-active");
				renderGames();
			});
		});

	})();
	</script>

</body>
</html>
