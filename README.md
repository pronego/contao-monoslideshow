# Contao Monoslideshow Bundle

## Überblick
Diese Erweiterung wurde ursprünglich von Daniel Ritter aus der Schweiz 
entwickelt (http://www.designr.ch) und bis ca. 2013 gewartet. 
Auf Wunsch von Lothar Schwalm (derzeitiger Sponsor, https://die-schreibmaus.de) 
wurde die Erweiterung 2023 nach vergeblichen Kontaktbemühungen zu dem Programmierer 
von Dr. Manuel Lamotte-Schubert [PRONEGO](https://www.pronego.com) übernommen 
und für Contao 5 angepasst.


## Voraussetzungen
Die [Monoslideshow](https://monoslideshow.com/) funktioniert nur in Verbindung mit einer Javascript-Datei, 
die bis 2023 auf der [Website](https://monoslideshow.com/) erworben werden konnte (Kosten: damals ca. 25 €). 
Mittlerweile hat der Entwickler der Monoslideshow, Wimer Hazenberg, das Projekt jedoch eingestellt.
Man kann ihn aber über seine aktuelle [Website](https://monokai.com/) kontaktieren und für eine Monoslideshow-Lizenz anfragen.

Wer bereits eine Monoslideshow-Lizenz besitzt, kann sie mit dieser Erweiterung ab Contao 5 
weiterhin benutzen.

## Demo
Wer sich die wunderbaren Effekte der Monoslideshow einmal in Live-Galerien ansehen möchte, 
bevor er/sie sich die Mühe macht, den Kauf der Javascript-Datei bei [Wimer Hazenberg](https://monokai.com) 
zu erfragen, kann das unter folgendem Link tun:

> [Demo auf die-schreibmaus.de](https://die-schreibmaus.de/ganz-spezielle-galerien.html)


## Installation
Die Installation des Bundles erfolgt
1. über den Contao-Manager. Hierfür einfach im Contao-Store auf Installieren klicken und
    Anschluss die erforderlichen Datenbankänderungen durchführen.

2. via Composer:
    ```
    composer require pronego/contao-monoslideshow
    ```
   Die Datenbankänderungen können im Anschluss durch Aufruf von
    ```
    vendor/bin/contao-console contao:migrate
   ```
   durchgeführt werden.


Nach der Installation muss die separat erworbene (siehe [Voraussetzungen](#voraussetzungen)) 
Javascript-Datei `monoslideshow.js` in den Ordner `public` der Erweiterung kopiert werden.
Dieser Ordner befindet sich üblicherweise unter `vendor/contao-monoslideshow/`.
Danach ist die Erweiterung einsatzbereit und man kann die Monoslideshow im Backend 
als Inhaltselement oder Modul auswählen.