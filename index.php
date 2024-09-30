<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Francesco Giambenedetti, studente Full Stack, presenta i suoi lavori in campo informatico.">
    <meta name="keywords" content="FULL STACK, CODING, UI, UX, PHOTOSHOP, FIGMA, HTML, HTML5, CSS, SCSS, SASS, JAVASCRIPT, PHP, SQL">
    <link rel="stylesheet" href="css/style.min.css" title="foglio di stile css">
    <link href="./img/favicon/favicon.png" title="Favicon sito" rel="icon" type="image/x-icon">
    <title>Francesco Giambenedetti</title>
</head>
<body>
    <?php
    if (empty($_SESSION['csrf_token'])) { /* genera e gestisce CSRF token (sistema di sicurezza) */
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <div id="cookieConsentBanner" class="cookie-consent-banner"> <!-- generato con iubenda per la privacy -->
        <p>Il sito utilizza cookies per garantire una migliore esperienza utente: leggi la nostra <a href="https://www.iubenda.com/privacy-policy/68179801/cookie-policy" class="iubenda-black iubenda-noiframe iubenda-embed iubenda-noiframe " title="Cookie Policy ">Cookie Policy</a></p>
        <button id="acceptCookies" class="cookie-button">Accetta</button>
        <button id="rejectCookies" class="cookie-button">Rifiuta</button>
        <script type="text/javascript">
            (function(w, d) {
                var loader = function() {
                    var s = d.createElement("script"),
                        tag = d.getElementsByTagName("script")[0];
                    s.src = "https://cdn.iubenda.com/iubenda.js";
                    tag.parentNode.insertBefore(s, tag);
                };
                if (w.addEventListener) {
                    w.addEventListener("load", loader, false);
                } else if (w.attachEvent) {
                    w.attachEvent("onload", loader);
                } else {
                    w.onload = loader;
                }
            })(window, document);
        </script>
    </div>
    <header class="header">
        <div class="menu-icon" onclick="toggleMenu()"> <!-- invocata la funzione "toggleMenu" al clic -->
            <div class="linea"></div> <!-- 3 linee per il menu hamburger -->
            <div class="linea"></div>
            <div class="linea"></div>
        </div>
        <div class="logo">
            <img src="img/logo/logo.png" title="Logo" alt="Logo" class="img-logo">
        </div>
        <?php
        require 'connessioneDatabase.php'; /* richiama il file per la connessione al database */
        $stmt = $pdo->prepare('SELECT * FROM navbar_links'); /* query che seleziona tutti i valori dalla tabella "navbar_links" (su phpmyadmin) */
        $stmt->execute(); /* esecuzione della query */
        $voci_menu = $stmt->fetchAll(PDO::FETCH_ASSOC); /* la variabile "$voci_menu" registra i risultati della query come un array associativo */
        ?>
        <nav class="navbar">
            <ul class="menu" id="menu">
                <?php foreach ($voci_menu as $voce): ?>  <!-- il ciclo foreach itera su ogni voce della tabella nel database -->
                    <li><a href="<?php echo $voce['link']; ?>" title="<?php echo $voce['title']; ?>"><?php echo $voce['etichetta']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="side-menu" id="sideMenu">
            <a href="javascript:void(0)" class="chiudiMenu" onclick="toggleMenu()" title="Chiudi menù">&times;</a>
            <ul class="menu" id="menu">
                <?php foreach ($voci_menu as $voce): ?>
                    <li><a href="<?php echo $voce['link']; ?>" title="<?php echo $voce['title']; ?>"><?php echo $voce['etichetta']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </header>
    <main>
        <div class="cards-container">
            <div class="benvenuto-card" id="benvenuto">
                <h1>BENVENUTO</h1>
                <p>Mi chiamo Francesco Giambenedetti e sono uno studente Full Stack presso l'Accademia CODE di Torino.</p>
                <p>Il mio percorso accademico mi ha portato a Bologna a studiare presso il Conservatorio G.B. Martini, dove mi sono laureato nel 2020 in Musica Elettronica e Sound Design.</p>
                <p>Accanto alla Musica, ho lavorato per 4 anni nel mondo del marketing diretto e nell’ambito delle risorse umane.</p>
                <?php $stmt = $pdo->prepare('SELECT * FROM carousel_images ORDER BY last_modified DESC'); /* seleziona tutte le voci della tabella "carousel_images" */
                $stmt->execute();
                $carousel_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="carosello-container">
                    <div class="carosello" id="carosello">
                        <?php foreach ($carousel_images as $image): ?>
                            <div class="carosello-item">
                                <img src="img/carosello/<?php echo $image['src']; ?> " alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>" class="carosello-img">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="social-links">
                    <a href="https://www.instagram.com/francescogiambenedetti/" target="_blank"> <img src="img/social/instagram.png" alt="Seguimi su Instagram" class="img-social" title="Seguimi su Instagram"></a>
                    <a href="https://it.linkedin.com/in/francesco-giambenedetti-269367309?trk=public_post_feed-actor-name&original_referer=https%3A%2F%2Fwww.google.com%2F" target="_blank"><img src="img/social/linkedin.png" alt="Seguimi su Linkedin" class="img-social" title="Seguimi su Linkedin"></a>
                </div>
            </div>
            <div class="miPresento-card" id="miPresento">
                <h1>MI PRESENTO</h1>
                <div class="foto-container">
                    <img src="img/francesco/francesco.png" alt="Francesco Giambenedetti" class="img-francesco" title="Francesco Giambenedetti">
                </div>
                <p id=descrizione>Francesco Giambenedetti, 26 anni, Parma</p>
                <?php
                $stmt = $pdo->prepare('SELECT * FROM skills ORDER BY id ASC');
                $stmt->execute();
                $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="skills-section">
                    <h2>Competenze</h2>
                    <div id="skillsContainer">
                        <?php foreach ($skills as $skill): ?>
                            <div class="skill-item" data-level="<?php echo $skill['skill_level']; ?>">
                                <p><?php echo htmlspecialchars($skill['skill_name']); ?></p>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" style="width: <?php echo $skill['skill_level']; ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="contattami-button-container">
            <a href="#contattami" class="contattami-button" title="Contattami">Contattami</a>
        </div>
        </div>
        <div id="space">-</div>
        <div class="cosaFaccio-card">
            <div class="cosaFaccio-left">
                <h1>COSA FACCIO</h1>
                <p>Grazie all'Accademia Full Stack, ho approfondito sia l'aspetto grafico di un sito — UI e UX — che il suo funzionamento tecnico, coprendo sia il front-end che il back-end.</p>
                <p>L'intero sito è stato realizzato da me ed ha lo scopo di mostrare i miei lavori in campo informatico.</p>
                <p id=scrittaVideo>Nel video è presente il mio ultimo progetto realizzato.</p>
            </div>
            <?php
            $stmt = $pdo->prepare('SELECT * FROM videos');
            $stmt->execute();
            $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="cosaFaccio-right">
                <?php if (!empty($videos)): ?>
                    <video width="100%" height="auto" controls>
                        <source src="../video/<?php echo htmlspecialchars($videos[0]['src']); ?>" type="video/mp4">
                        Impossibile aprire il contenuto.
                    </video>
                <?php else: ?>
                    <p>Nessun video disponibile.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="progetti-button-container">
            <a href="#progetti" class="progetti-button" title="Progetti">Progetti</a>
        </div>
    </main>
    <div class="graficaFrontEnd">
        <div class="content-card">
            <h2>GRAFICA</h2>
            <p>L'occhio vuole la sua parte! Con PHOTOSHOP e FIGMA ho progettato una piattaforma dall’interfaccia moderna, pulita e user-friendly, garantendo un'esperienza visiva piacevole e funzionale.</p>
            <div class="loghi">
                <img src="img/grafica/photoshop.png" alt="Photoshop" title="Photoshop">
                <img src="img/grafica/figma.png" alt="Figma" title="Figma">
            </div>
        </div>
        <div class="content-card">
            <h2>FRONT-END</h2>
            <p>HTML, CSS e SASS sono i protagonisti di questa sezione: grazie a loro il design prende vita, permettendo all'utente di godere di un’esperienza di navigazione fluida e coinvolgente.</p>
            <div class="loghi">
                <img src="img/front-end/html.png" alt="HTML" title="HTML">
                <img src="img/front-end/css.png" alt="CSS" title="CSS">
                <img src="img/front-end/sass.png" alt="SASS" title="SASS">
            </div>
        </div>
        <div class="content-card">
            <h2>BACK-END</h2>
            <p>Il motore invisibile che fa funzionare tutto: PHP, JAVASCRIPT e SQL gestiscono la logica e il funzionamento di ogni sito o applicazione, garantendo stabilità e sicurezza.</p>
            <div class="loghi">
                <img src="img/back-end/php.png" alt="PHP" title="PHP">
                <img src="img/back-end/javascript.png" alt="JavaScript" title="JAVASCRIPT">
                <img src="img/back-end/sql.png" alt="SQL" title="SQL">
            </div>
        </div>
    </div>
    <?php
    $stmt = $pdo->prepare('SELECT * FROM projects');
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="progettiContainer">
        <div class="progetti-card" id="progetti">
            <h1>PROGETTI</h1>
            <ul class="progetti-list">
                <?php foreach ($projects as $project): ?>
                    <li class="progetti-item">
                        <div class="progetto-img-container">
                            <img src="<?php echo '../img/progetti/' . htmlspecialchars($project['img_principale']); ?>" alt="<?php echo htmlspecialchars($project['titolo_img_principale']); ?>" title="<?php echo htmlspecialchars($project['titolo_img_principale']); ?>">
                        </div>
                        <div class="progetto-description">
                            <h2><?php echo htmlspecialchars($project['titolo_principale']); ?></h2>
                            <p><?php echo htmlspecialchars($project['testo_principale']); ?></p>
                            <button class="scopri-di-piu" data-project-id="<?php echo $project['id']; ?>">Scopri di più</button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div id="projectModal" class="modale">
        <div class="contenuto-modale">
            <span class="chiudi">&times;</span>
            <h2 id="titolo-modale"></h2>
            <br>
            <p id="descrizione-modale"></p>
            <p class="specifiche-progetti"><strong>Data:</strong> <span id="data-modale"></span></p>
            <p class="specifiche-progetti"><strong>Cliente:</strong> <span id="cliente-modale"></span></p>
            <p class="specifiche-progetti"><strong>Programmi utilizzati:</strong> <span id="strumenti-modale"></span></p>
            <div class="immagine-modale">
                <img id="immagine-modale" src="" alt="Immagine progetto">
            </div>
            <h3 id="titolo-card-modale" style="display:none;"></h3>
            <p id="testo-card-modale" style="display:none;"></p>
            <p><a id="link-modale" href="#" target="_blank">Vai al progetto</a></p>
        </div>
    </div>
    <div class="form-container">
        <div class="contattami-card" id="contattami">
            <div class="contattami-left">
                <h1>CONTATTAMI</h1>
                <p>Per maggiori informazioni o per una collaborazione non esitare a contattarmi tramite il form sottostante.</p>
                <form action="messaggiForm.php" method="POST" id=contattamiForm novalidate title="Form contatto"> <!-- specifica che i dati inviati (tramite post per sicurezza) andranno su "messaggiForm.php" -->
                    <fieldset>
                        <legend>* Campo obbligatorio</legend>
                        <input type="text" id="nome" name="nome" placeholder="Nome *" maxlength="50" value="<?php echo isset($_SESSION['data']['nome']) ? htmlspecialchars($_SESSION['data']['nome']) : ''; ?>" class="<?php echo isset($_SESSION['errors']['nome']) ? 'form-errore' : ''; ?>">
                        <input type="text" id="cognome" name="cognome" placeholder="Cognome *" maxlength="50" value="<?php echo isset($_SESSION['data']['cognome']) ? htmlspecialchars($_SESSION['data']['cognome']) : ''; ?>" class="<?php echo isset($_SESSION['errors']['cognome']) ? 'form-errore' : ''; ?>">
                        <input type="email" id="email" name="email" maxlength="50" placeholder="<?php echo isset($_SESSION['errors']['email']) ? 'Indirizzo email non valido' : 'Email *'; ?>"
                            value="<?php echo isset($_SESSION['data']['email']) ? htmlspecialchars($_SESSION['data']['email']) : ''; ?>"
                            class="<?php echo isset($_SESSION['errors']['email']) ? 'form-errore' : ''; ?>"
                            onfocus="clearError(this)">
                        <input type="tel" id="telefono" name="telefono" placeholder="Telefono" maxlength="18" value="<?php echo isset($_SESSION['data']['telefono']) ? htmlspecialchars($_SESSION['data']['telefono']) : ''; ?>">
                        <select id="argomento" name="argomento" class="<?php echo isset($_SESSION['errors']['argomento']) ? 'form-errore' : ''; ?>">
                            <option value="" disabled selected>Seleziona un argomento *</option>
                            <option value="altro">Progetti</option>
                            <option value="altro">Collaborazione</option>
                            <option value="altro">Altro</option>
                        </select>
                        <input type="text" id="oggetto" name="oggetto" placeholder="Oggetto *" maxlength="50" value="<?php echo isset($_SESSION['data']['oggetto']) ? htmlspecialchars($_SESSION['data']['oggetto']) : ''; ?>" class="<?php echo isset($_SESSION['errors']['oggetto']) ? 'form-errore' : ''; ?>">
                        <textarea id="testo" name="testo" placeholder="Messaggio *" maxlength="100"></textarea>
                        <p id="paroleRimaste">Caratteri rimasti: 100</p>
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="privacy-checkbox">
                            <div class="checkbox-container">
                                <input type="checkbox" id="privacy" name="privacy" required>
                            </div>
                            <div class="label-container">
                                <label for="privacy">Ho letto e accetto la <em><a href="https://www.iubenda.com/privacy-policy/68179801" class="iubenda-black iubenda-noiframe iubenda-embed iubenda-noiframe " title="Privacy Policy ">Privacy Policy</a></em> *</label>
                                <script type="text/javascript">
                                    (function(w, d) {
                                        var loader = function() {
                                            var s = d.createElement("script"),
                                                tag = d.getElementsByTagName("script")[0];
                                            s.src = "https://cdn.iubenda.com/iubenda.js";
                                            tag.parentNode.insertBefore(s, tag);
                                        };
                                        if (w.addEventListener) {
                                            w.addEventListener("load", loader, false);
                                        } else if (w.attachEvent) {
                                            w.attachEvent("onload", loader);
                                        } else {
                                            w.onload = loader;
                                        }
                                    })(window, document);
                                </script>
                            </div>
                        </div>
                        <div id="formResult" class="form-result"></div>
                        <div class="form-button-container">
                            <button type="submit" class="inviaForm-button" title="Invia form">Invia</button>
                            <button type="reset" id="resetButton" class="annullaForm-button" title="Resetta form">Annulla</button>
                            <?php
                            unset($_SESSION['errors']);
                            unset($_SESSION['data']);
                            session_start();
                            if (isset($_SESSION['message'])) {
                                $messageClass = $_SESSION['message_type'] == 'success' ? 'success' : 'error';
                                echo '<div class="' . $messageClass . '">' . $_SESSION['message'] . '</div>';
                                unset($_SESSION['message']);
                                unset($_SESSION['message_type']);
                            }
                            ?>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="contattami-right">
                <img src="img/form/form.png" alt="Contattami" class="img-contattami" title="Contattami">
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Francesco Giambenedetti. Tutti i diritti riservati.</p>
            <a href="#" id="modificaCookies"><em>Modifica preferenze cookies</em></a>
            <div class="footer-social-links">
                <a href="https://www.instagram.com/francescogiambenedetti/" target="_blank">
                    <img src="img/social/instagram.png" alt="Seguimi su Instagram" class="footer-img-social" title="Seguimi su Instagram">
                </a>
                <a href="https://it.linkedin.com/in/francesco-giambenedetti-269367309?trk=public_post_feed-actor-name&original_referer=https%3A%2F%2Fwww.google.com%2F" target="_blank">
                    <img src="img/social/linkedin.png" alt="Seguimi su LinkedIn" class="footer-img-social" title="Seguimi su LinkedIn">
                </a>
            </div>
        </div>
    </footer>
    <script src="./js/accettaCookies.js" title="Script per accettare o rifiutare i Cookies"></script>
    <script src="./js/menuHamburger.js" title="Script menù hamburger"></script>
    <script src="./js/carosello.js" title="Script immagini carosello"></script>
    <script src="./js/skills.js" tite="Script gestionen skills"></script>
    <script src="./js/gestioneProgetti.js" title="Script per i progetti"></script>
    <script src="./js/erroriForm.js" title="Script per gestire gli errori del form"></script>
    <script src="./js/caratteriRimasti.js" title="Script caratteri rimasti"></script>
</body>
</html>