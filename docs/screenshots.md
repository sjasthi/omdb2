# Screenshots of the final application

Captured from a fresh GitHub installation on November 9, 2026, using the fictional data in [examples](../examples/README.md). No production data, account passwords, or private configuration appears in these images. Desktop captures use 1366 × 900; the phone capture uses 390 × 844. The search screenshot is scrolled to show both the filters and result.

## Home

Catalog counts and the starting links work after importing the three sample CSV files.

![Home page showing 3 movies, 3 people and 1 song](screenshots/home.jpg)

## Sign in

Use the account you created during installation. This form intentionally shows no preset credentials.

![Empty sign-in form](screenshots/login.jpg)

## Member catalog

Members see View actions and no Admin menu. Search, filters and pagination remain available.

![Member movie catalog with View actions only](screenshots/member-catalog.jpg)

## Administrator catalog

Administrators receive Create, Edit, People, Songs, More details and Delete controls. The endpoints still check the administrator role.

![Administrator catalog with management actions](screenshots/admin-catalog.jpg)

## Combined cast and director search

The fictional cast member Avery Stone and director Morgan River are both credited on Harbor Lights. Every filled filter must match the same movie.

![Combined cast and director search returning Harbor Lights](screenshots/combined-search.jpg)

## Administrator imports

Select the record type and a UTF-8 CSV file. The form documents column order and atomic import behavior.

![Administrator CSV import form](screenshots/admin-import.jpg)

## Create movie

Required fields, length/year limits and Cancel are shown in the form.

![Create movie form with labels and limits](screenshots/create-movie.jpg)

## Help

The in-app guide covers browsing, searching, permissions, small screens and admin workflows.

![Help and getting started](screenshots/help.jpg)

## Phone layout

Navigation collapses behind Menu and catalog cards stack vertically. Wide tables scroll within their own region.

![Home page at a phone width](screenshots/phone-home.jpg)
