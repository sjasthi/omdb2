# Shared UI dependencies

The existing Bootstrap 3.3.7 and DataTables 1.10.12 assets are served locally so the shared layout and table controls do not depend on CDN availability. jQuery 3.2.1, Select2, and Font Awesome remain in their existing local folders. Upstream version numbers and license notices are retained in the downloaded files. This refactor consolidates the existing libraries; it does not upgrade their major versions.

Sources: https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/ and https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/.
Bootstrap: https://github.com/twbs/bootstrap/blob/v3.3.7/LICENSE
DataTables: https://datatables.net/license/mit

Anagram animations (Animate.css 4.1.1) and notifications (Toastr) are also served locally, retaining their upstream license headers. The former duplicate jQuery load on the anagram page is removed. External puzzle API requests remain separate from these static UI assets.
