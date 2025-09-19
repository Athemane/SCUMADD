***

# SCUM Server Admin Dashboard

A web-based tool to monitor, manage, and moderate your SCUM game server in real time. It offers secure login, live statistics, player and vehicle management, and essential admin features via an ergonomic dashboard.

***

### Features

- **Secure authentication**: Registration, login, and password reset with email code verification[1].
- **Live statistics**: Real-time display of online players, admins present, and vehicles on the server[3].
- **Dashboard views**: Search and filter players and vehicles, access quick actions (restart, backup, clear logs)[1][3].
- **Responsive UI**: Modern, dark-themed HTML/CSS frontend optimized for desktops[4][1].
- **Automated updates**: Server/player/vehicle info refreshes in the background every few seconds[3].
- **SCUM server integration**: Uses Steam Query and RCON to fetch game data and vehicle information[2].
- **Modular codebase**: JavaScript (frontend), PHP (API and backend), CSS (styling).

***

### Installation

1. **Clone the repository:**

   ```sh
   git clone https://github.com/yourusername/scum-server-dashboard.git
   cd scum-server-dashboard
   ```

2. **Setup the backend:**

   - Deploy all `.php` files to a web server with PHP and MySQL/MariaDB (for account and session management)[1][2].
   - Adjust database credentials in files like `login.php` and `register.php`.
   - Set your SCUM server's IP, RCON port and password in `scum-server.php`[2].

3. **Configure PHPMailer (optional):**

   - For email-based password reset, install PHPMailer using Composer or manually include it in your backend (see `send_reset_code.php`, `test_mail.php`)[5][6].
   - Update mail server credentials in the corresponding PHP files.

4. **Frontend setup:**

   - All HTML/CSS/JS files can be hosted as static content; open `index.html` to access the app[1][4][3].

***

### Usage

- Access `index.html` to register or log in as a server administrator.
- After login, view online players, connected admins, and vehicles in real time.
- Use dashboard controls to search, filter, and take quick actions (restart server, clear logs, backup)[3].
- Password reset is available from the login page via email verification.

***

### Prerequisites

- Web server with PHP 7.2+ and MySQL/MariaDB.
- Working SCUM game server (with Steam Query and RCON enabled)[2].
- SMTP server for email notifications if password reset is required[5][6].
- [PHPMailer](https://github.com/PHPMailer/PHPMailer) if you use email-based functions (LGPL licensed but not included here).

***

### File Overview

| File               | Role/Function                                       |
|--------------------|-----------------------------------------------------|
| index.html         | Main interface and dashboard[1]                     |
| style.css          | Responsive dark theme styling[4]                    |
| script.js          | Frontend JS for live refresh, table updates[3]      |
| scum-server.php    | Server query and logic (players, vehicles)[2]       |
| login.php          | User login with session creation                    |
| register.php       | User registration                                   |
| players.php        | API for listing and searching players               |
| send_reset_code.php| Email verification for password reset               |
| test_mail.php      | SMTP email test utility                             |
| authMiddleware.js  | API key check (for future REST API use)             |

***

### Contributing

Pull requests, issues, and feature suggestions are welcome! Please fork the repo and open a PR to propose improvements.

***

### License

No explicit license file is included. If you wish to use or modify this project, add an open-source license of your choice (MIT, GPL, Apache, etc)[1][2][3]. PHPMailer is LGPL (not bundled, see its repository for details)[6][5].

***

### Acknowledgements

- Inspired by the needs of SCUM game server admin communities.
- Icons: [Flaticon](https://www.flaticon.com/).
- Fonts: [Google Fonts](https://fonts.google.com/specimen/Montserrat).

***

This README covers setup, usage, file roles, requirements, and licensing to help users and contributors quickly get started with your SCUM server dashboard[1][2][3][4].

Sources
[1] index.html https://ppl-ai-file-upload.s3.amazonaws.com/web/direct-files/attachments/48889400/850693ec-0d99-497e-99cf-9dafcdfc85d5/index.html
[2] scum-server.php https://ppl-ai-file-upload.s3.amazonaws.com/web/direct-files/attachments/48889400/24c465da-26cc-4a3c-9500-42a150f723f4/scum-server.php
[3] script.js https://ppl-ai-file-upload.s3.amazonaws.com/web/direct-files/attachments/48889400/82ebb2f9-e917-45e7-a0bb-623fd18ea0db/script.js
[4] style.css https://ppl-ai-file-upload.s3.amazonaws.com/web/direct-files/attachments/48889400/f04d9b7b-89af-4347-96f1-408c62bfc95e/style.css
[5] send_reset_code.php https://ppl-ai-file-upload.s3.amazonaws.com/web/direct-files/attachments/48889400/35881ffc-6d47-4af6-b441-97afcf8486a4/send_reset_code.php
[6] test_mail.php https://ppl-ai-file-upload.s3.amazonaws.com/web/direct-files/attachments/48889400/bea7202d-6b94-499b-b2fa-2c99391d4c0e/test_mail.php

