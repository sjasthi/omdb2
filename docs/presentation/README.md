# OMDB2 presentation kit

Prepared for Zach McGhan, Group 1, ICS 499. Target: **10 minutes total**, with seven minutes of slides and a three-minute live demo. Credit the original `sjasthi/omdb2` project and describe the improvements made in this iteration sequence.

- [Timed speaking outline and live demo](demo-script.md)
- [Likely professor questions and short answers](questions-and-answers.md)
- [Startup and rehearsal checklist](startup.md)
- [Offline screenshot walkthrough](offline-demo.html), which opens as a local file without a server
- [Editable PowerPoint](OMDB2-Presentation.pptx) and [static PDF backup](OMDB2-Presentation.pdf)
- [82-second silent screenshot video](OMDB2-Offline-Walkthrough.mp4) and [optional captions](walkthrough-captions.srt)
- [Presentation verification results](verification.md)

The local delivery folder also contains the editable slide deck, a PDF backup, and a silent walkthrough video. The video uses verified app screenshots and supports talking through the demo if the live server is unavailable. Generated account passwords and the machine-specific launcher stay outside Git.

The small finishing changes are a Recently added movies section and a Clear filters control on the three catalog tables. They use existing records and permissions. The CLI presentation preflight reads schema/account presence and the prepared cast/director match without modifying data.

## Day-of-presentation order

1. Open the private delivery folder and run **Start OMDB Demo.private.cmd** on Zach's PC. Read every PASS/FAIL line before opening slides.
2. Confirm both account logins using the private local login file. Sign out before the talk.
3. Open the slides and the local app in separate windows. Keep the offline PDF/video available.
4. Use the speaking notes as cues. Do not read the slides word for word.
5. Create and delete only the clearly named disposable record during the live demo. Preserve the fictional examples and user accounts.

The original local XAMPP database instance has a documented system-table problem. Zach's demonstration uses the clean MariaDB instance on port 3307. The launcher must receive that instance's configuration path, as the private CMD file does. A normal clean XAMPP installation usually uses port 3306.
