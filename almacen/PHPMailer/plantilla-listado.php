<?php

$html = <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Correo</title>
  <style type="text/css">
    body,
    html, 
    .body {
      background: #f3f3f3 !important;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif, Helvetica;
    }
  
    .container.header {
      background: #f3f3f3;
    }
  
    .body-drip {
      border-top: 8px solid #663399;
    }

    .text-right {
      text-align: right;
    }

    .btn{
      cursor: pointer;
      font-size: 14px;
      padding: 6px 12px;
      margin-bottom: 0;
      text-decoration: none;
      text-align: center;
      white-space: nowrap;
      user-select: none;
      background-image: none;
      border: 1px solid transparent;
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
      border-radius: .25rem;
    }

  </style>
</head>
<body>
  <spacer size="16"></spacer>
  
  <container class="header">
    <row class="collapse">
      <columns>
        <img src="https://chronox.me/img/core-img/logo.png" alt="" width="100px" height="25px">
      </columns>
    </row>
  </container>
  
  <container class="body-drip">
  
    <spacer size="16"></spacer>
  
    <spacer size="16"></spacer>
  
    <row>
      <columns>
        <h3 class="text-center">Listado de equipos</h3>
        <p class="text-right">$fechaAhora | $horaAhora</p>
      </columns>
    </row>
  
    <hr/>
  
    <row>
      <columns>
        <p class="text-center">$mensaje</p>
        <p class="text-center">$listaAsignaciones</p>
      </columns>
    </row>
  
  </container>
</body>
</html>
EOD;

?>
  
  
  