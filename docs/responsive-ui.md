# Responsive UI refactor — October 19, 2026

Issue: [#8](https://github.com/sjasthi/omdb2/issues/8).

The shared 1,000–1,250px fixed layout is replaced with a fluid shell in `nav.php`/`footer.php`, `styles/responsive.css`, and `scripts/layout.js`. At a 390px viewport, the original movie list extended to 1,258px. The new layout keeps document width within the viewport and puts wide data inside a horizontal scroll region.

- Below 992px, the main menu expands with a labeled button; section links wrap above the content. At 992px and wider, section links use a left sidebar. The shell fills available space up to a readable 1,600px maximum.
- Forms and search fields fit their parent, use at least 16px input text, and retain browser zoom. Primary controls and row actions have at least 44px touch targets. Checkbox/radio sizing remains distinct from text input sizing.
- Tables retain every column and action. After DataTables initialization, each table gets a bounded scroll region, a visible sideways-scroll hint when needed, and keyboard focus only when overflowing. Filtering, sorting and pagination remain available. Native tables are wrapped after load.
- The mobile menu exposes its state with `aria-expanded`, supports Escape, and stays visible if JavaScript is disabled. A skip link and visible keyboard focus support navigation. Page authorization and CSRF checks are unchanged.
- Poster enlargement uses a viewport-bounded dialog instead of scaling an image beyond the page. Anagram letter rows wrap; game CSS is scoped to the game instead of changing the global body layout.
- Duplicate document wrappers, broad form sizing, repeated jQuery/library includes, the fixed table shell, and absolute account-form placement are removed. The report table is properly closed. Create links have visible labels and no longer nest links inside buttons.
- Existing Bootstrap/DataTables/Select2/Font Awesome styling is retained. Shared CSS/JS/fonts and anagram animation/notification assets are served locally, with upstream notices retained under `vendor/`. There is one shared jQuery load. See `vendor/README.md` for versions and license sources. A major library-version upgrade is outside this layout refactor.

## Browser verification

Chromium viewport checks covered **24 representative pages at each of six sizes (144 checks)**:

| Profile | Viewport |
|---|---|
| Narrow phone | 320 × 800 |
| Phone | 390 × 844 |
| iPad portrait | 768 × 1024 |
| iPad landscape | 1024 × 768 |
| Laptop | 1366 × 768 |
| Desktop | 1920 × 1080 |

All checks passed: the measured viewport matched the requested width, document width stayed within it, main content existed, and the footer remained outside main content. These are browser viewport simulations, not physical-device or Safari certification. Measured results are saved in `docs/responsive-layout-results.json`.

The pages include Home, Movies, People, Songs, movie details/data/numbers, reports, search, About, Help, games, anagram creator/player, create/edit forms, relationship forms, CSV setup, and account creation. The three advanced search modes were additionally checked at 320px; no input/select controls overflowed. Sign-out confirmation also uses the shared shell.

Interaction checks passed: open menu and navigate; Escape closes the menu and restores focus; table filtering returns matching records; ArrowRight scrolls the focused table region; menu and all six movie row actions meet the 44px target; the poster dialog stays within a 320px viewport and closes; the basic-user movie list exposes only View and no Setup navigation. The supplied admin and basic-user logins both worked, and passwords/roles were not changed.

The isolated HTTP security regression suite passed all 291 checks after the shared layout refactor. See the PR checks for backend, syntax, and secret-scan results. For future changes, repeat the six-size matrix, test an admin and regular user, and verify table scrolling rather than hiding columns or clipping the whole page.

Implementation references: [Bootstrap responsive tables](https://getbootstrap.com/docs/3.4/css/#responsive-tables), [DataTables initialization event](https://datatables.net/reference/event/init).
