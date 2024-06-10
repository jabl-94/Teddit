<?php 
function tempoTrascorso($dataStringa) {
    $date = new DateTime($dataStringa); // La data specificata
    $now = new DateTime(); // Un altro oggetto DateTime per salvare il momento attuale
    $interval = $date->diff($now); // Calcola la differenza tra i due
    
    // Salva gli intervali da mosrtrare poi
    $anni = $interval->y;
    $mesi = $interval->m;
    $giorni = $interval->d;
    $ore = $interval->h;
    $minuti = $interval->i;
    $secondi = $interval->s;
    
    $output = '';
    
    // Verifica lo stato degli intervali e cambia il messaggio a seconda della distanza 
    // dal momento in cui e' stato creato il contenuto
    if ($anni > 0) {
        $output = $anni . ' ' . ($anni > 1 ? 'years' : 'year');
    } elseif ($mesi > 0) {
        $output = $mesi . ' ' . ($mesi > 1 ? 'months' : 'month');
    } elseif ($giorni > 0) {
        $output = $giorni . ' ' . ($giorni > 1 ? 'days' : 'day');
    } elseif ($ore > 0) {
        $output = $ore . ' ' . ($ore > 1 ? 'hours' : 'hour');
    } elseif ($minuti > 0) {
        $output = $minuti . ' ' . ($minuti > 1 ? 'minutes' : 'minute');
    } else {
        $output = $secondi . ' ' . ($secondi != 1 ? 'seconds' : 'second');
    }
    
    // concatena 'ago' nel messaggio
    return $output  . " ago";
    }
?>