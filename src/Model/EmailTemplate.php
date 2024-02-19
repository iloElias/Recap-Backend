<?php

namespace Ipeweb\IpeSheets\Model;

class EmailTemplate
{
    public static string $template = '
        <!DOCTYPE html
            PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recap</title>
            <style type="text/css">
                * {
                    margin: 0;
                    padding: 0;
                    text-align: center;
                    min-width: none;
                    box-sizing: border-box;
                    font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
                }

                body {
                    background-color: #212121;

                    display: flex;
                    flex-direction: column;
                }

                header,
                main {
                    height: min-content;
                    background-color: #fafafa;
                }

                header {
                    display: grid;
                    place-items: center;
                }

                header img {
                    width: 120px;

                    content: fit-content;
                    align-content: center;
                }

                main {
                    display: flex;
                    flex-direction: column;
                    align-items: center;

                    padding-bottom: 15px;
                }

                main p {
                    margin: 15px;
                }

                main a {
                    margin: 15px;

                    text-decoration: none;
                    color: #fafafa;

                    background-color: #212121;
                    padding: 8px;
                    border-radius: 8px;
                }

                footer * {
                    color: #adadad;
                    font-size: 12px;
                }

                footer {
                    padding: 20px 15px;

                    align-self: flex-end;

                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                }
            </style>
        </head>

        <body>
            <header>
                <img src="https://i.imgur.com/2UUTU5a.png" alt="Recap logo"> <!-- Recap logo -->
            </header>
            <main>
                <p>Some one gave you permission to see their project</p>
                <a href="invited-project-page">Click here to be redirected</a>
            </main>

            <footer>
                <p>If you do not want to receive invite emails, please <a href="login-page">log into your account</a> and
                    disable
                    notifications</p>
                <p>See more information about our service on <a href="about-us-page">about recap</a></p>
            </footer>
        </body>

        </html>
    ';
}
