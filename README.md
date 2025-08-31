# ICS-Todos to Markdown

Converts an ics file with VTODO items to a markdown task list.

## Installation

- Install [Git](https://git-scm.com/), [PHP](https://www.php.net/manual/de/install.php), and [composer](https://getcomposer.org/).
- `git clone https://github.com/leonickl/ics-todos-to-markdown`
- `cd ics-todos-to-markdown`
- `composer install`

## Usage

- `php index.php /path/to/tasks.ics` outputs the markdown string to standard output.
- `php index.php /path/to/tasks.ics > tasks.md` saves the markdown to the specified file.
