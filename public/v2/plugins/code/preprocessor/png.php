<?php

header('Content-Type: image/png');
header('X-Content-Quality: '.$_GET['quality']);
readfile($file);
