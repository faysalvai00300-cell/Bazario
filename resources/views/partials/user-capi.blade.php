<?php
        $userCapiData = [];
        if(auth()->check()) {
            $userCapiData['em'] = hash('sha256', strtolower(trim(auth()->user()->email)));
            if(auth()->user()->phone) $userCapiData['ph'] = hash('sha256', preg_replace('/[^0-9]/', '', auth()->user()->phone));
            $names = explode(' ', auth()->user()->name);
            $userCapiData['fn'] = hash('sha256', strtolower(trim($names[0])));
        }
    ?>
