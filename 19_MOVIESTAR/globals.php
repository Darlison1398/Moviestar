<?php
  # AQUI, VAMOS COLOCAR AS VARIÁVEIS GLOBAIS. Poderiamos colocar ips de api, e outros;

  session_start();
  $BASE_URL = "http://" . $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"]."?"). "/";