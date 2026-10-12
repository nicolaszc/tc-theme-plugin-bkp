<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="height=device-height, width=device-width, minimum-scale=1">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="assets/css/main.css" rel="stylesheet" />
<script src="assets/js/color-modes.js"></script>
<script src="assets/js/fontawesome.js"></script>
<script src="assets/js/jquery.min.js"></script>
</head>
<body>
<div class="wa-box">
<span class="brand d-flex align-items-center justify-content-center" href="#">
        <img src="./assets/img/iso-TC.svg" alt="TC Logo" class="logo">
        <sup>©</sup>       
    <p>WA Sender Pro</p>
</span>

<?php
function split_phone($phone) {
  // Split the phone number into parts
  $phone_number = substr($phone, -8);
  $phone_number = str_split($phone_number, 4);
  $phone_number = implode(' ', $phone_number);
  // Get the first part (the country code)
  $country_code = substr($phone, 0, 2);
  // Get the second part (celular prefix)
  $celular_prefix_number = substr($phone, 2, 1);
  // Get the second part (the area code)
  if ($country_code != 56) {
    $area_code = substr($phone, 3, 2);
  } else {
    $area_code = '';
  }
 
  // Final number
  $phone_number = '+(' .$country_code . ') ' . $celular_prefix_number. ' ' .$area_code . ' ' . $phone_number;

  // Return the formatted phone number
  return $phone_number;
}

$json_file = __DIR__ . '/modelos_data.json';
  // Lista de modelos con nombre y teléfono
  /* $modelos_base = [
    ['nombre' => 'Sebastian', 'telefono' => '+56944655947', 'user' => 'sebastian'],
    ['nombre' => 'Jess', 'telefono' => '+56938670216', 'user' => 'jess'],
    ['nombre' => 'Jeda', 'telefono' => '+56991820328', 'user' => 'jeda'],
    ['nombre' => 'Tayla', 'telefono' => '+56946852830', 'user' => 'tayla'],
    ['nombre' => 'Maddilyn', 'telefono' => '+56969180435', 'user' => 'maddilyn'],
    ['nombre' => 'Alice', 'telefono' => '+56945248683', 'user' => 'alice'],
    ['nombre' => 'Mia', 'telefono' => '+56945868999', 'user' => 'mia'],
    ['nombre' => 'Isabel', 'telefono' => '+56985094574', 'user' => 'isabel'],
    ['nombre' => 'Heloisa', 'telefono' => '+56937892836', 'user' => 'heloisa'],
    ['nombre' => 'Yunaa', 'telefono' => '+56933223371', 'user' => 'yunaa'],
    ['nombre' => 'Catarinna', 'telefono' => '+56955210255', 'user' => 'catarinna'],
    ['nombre' => 'Amara', 'telefono' => '+56971669261', 'user' => 'amara'],
    ['nombre' => 'Bela', 'telefono' => '+56945237669', 'user' => 'bela'],
    ['nombre' => 'Valentina', 'telefono' => '+56985762690', 'user' => 'valentina'],
    ['nombre' => 'Ray', 'telefono' => '+56974412442', 'user' => 'ray'],
    ['nombre' => 'Luanna', 'telefono' => '+56937852580', 'user' => 'Luanna'],
    ['nombre' => 'Margarita', 'telefono' => '+56990179982', 'user' => 'margarita'],
    ['nombre' => 'Kimberly', 'telefono' => '+56963507544', 'user' => 'kimberly'],
    ['nombre' => 'Olivia', 'telefono' => '+56956628728', 'user' => 'olivia'],
    ['nombre' => 'Ranya', 'telefono' => '+56952227644', 'user' => 'ranya'],
    ['nombre' => 'Belinda', 'telefono' => '+56998631662', 'user' => 'belinda'],
    ['nombre' => 'Lucy', 'telefono' => '+56942884835', 'user' => 'lucy'],
    ['nombre' => 'Linny', 'telefono' => '+56965309459', 'user' => 'linny'],    
    ['nombre' => 'Chiara', 'telefono' => '+56974067166', 'user' => 'chiara'],
    ['nombre' => 'Londy', 'telefono' => '+56948892941', 'user' => 'londy'],
    ['nombre' => 'Brandy', 'telefono' => '+56950706243', 'user' => 'brandy'],
    ['nombre' => 'Alinna', 'telefono' => '+56942330798', 'user' => 'alinna'],
    ['nombre' => 'Tatiana', 'telefono' => '+56973305471', 'user' => 'tatiana'],
    ['nombre' => 'Camila', 'telefono' => '+56990576855', 'user' => 'camila'],
    ['nombre' => 'Naomi', 'telefono' => '+56987026396', 'user' => 'naomi'],
    ['nombre' => 'Louise', 'telefono' => '+56985222259', 'user' => 'Louise'],
    ['nombre' => 'Nataly', 'telefono' => '+56961212818', 'user' => 'nataly'],
    ['nombre' => 'Brigiette', 'telefono' => '+56926866029', 'user' => 'brigiette'],
    ['nombre' => 'Amalia', 'telefono' => '+56926330142', 'user' => 'amalia'],
    ['nombre' => 'Marcely', 'telefono' => '+56936921765', 'user' => 'marcely'],
    ['nombre' => 'Bevini', 'telefono' => '+56965076059', 'user' => 'bevini'],
    ['nombre' => 'Eva', 'telefono' => '+56944787905', 'user' => 'eva'],
    ['nombre' => 'Rebeca', 'telefono' => '+56926014979', 'user' => 'rebeca'],
    ['nombre' => 'Megan', 'telefono' => '+56949169152', 'user' => 'megan'],
    ['nombre' => 'Ivy', 'telefono' => '+56930898635', 'user' => 'ivy'],
    ['nombre' => 'Colucci', 'telefono' => '+56965326189', 'user' => 'colucci'],
    ['nombre' => 'Juliana', 'telefono' => '+56937647464', 'user' => 'juliana'],
    ['nombre' => 'Laisa', 'telefono' => '+56994025636', 'user' => 'laisa'],
    ['nombre' => 'Melli', 'telefono' => '+56976473676', 'user' => 'melli'],
    ['nombre' => 'Ciara', 'telefono' => '+56976829557', 'user' => 'ciara'], 
    ['nombre' => 'Alexa', 'telefono' => '+56988272214', 'user' => 'alexa'],
    ['nombre' => 'Renata', 'telefono' => '+56940967474', 'user' => 'renata'],
    ['nombre' => 'Hazel', 'telefono' => '+56962765765', 'user' => 'hazel'],
    ['nombre' => 'Munique', 'telefono' => '+56951023206', 'user' => 'munique'],
    ['nombre' => 'July', 'telefono' => '+56951810476', 'user' => 'july'],
    ['nombre' => 'Constanza', 'telefono' => '+56934821044', 'user' => 'constanza'],
  // ... el resto igual
]; */

$host = $_SERVER['HTTP_HOST'];
$env = str_contains($host, '.local') ? 'local' : 'prod';

$context = null;
if ($env === 'local') {
    $context = stream_context_create([
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ]
    ]);
}

$url_endpoint = 'http://topclass-girls.com/wp-json/topclass/v1/modelos';
$json = file_get_contents($url_endpoint, false, $context);
$data = json_decode($json, true);

function sortAlphabetically($a, $b) {
    return strcmp($a['nombre'], $b['nombre']);
}

uasort($data, 'sortAlphabetically');

$modelos_base = [];
// Inyectar usuario de prueba siempre
$modelos_base[] = [
    'nombre' => 'Sebastian',
    'telefono' => '+56944655947',
    'user' => 'sebastian'
];

if (is_array($data)) {
    foreach ($data as $user => $info) {
        $modelos_base[] = [
            'nombre' => $info['nombre'],
            'telefono' => $info['telefono'],
            'user' => $user
        ];
    }
}else{
    echo 'No se pudo decodificar el JSON desde: ' .$url_endpoint;
}

$mensajes_dia = [
"Hola {nombre}! Bienvenida a TopClass.
https://topclass-girls.com/

Queríamos contarte algo especial, has sido seleccionada como una de las de TopClass.

Desde hoy tienes acceso exclusivo al sitio donde podrás ver tu propio espacio personal de venta, diseñado para que promociones tu contenido y servicios de forma profesional

En Topclass la exclusividad no la pagas tú: la pagan los clientes que buscan un espacio más exclusivo, selecto y serio. Nuestro valor es alrededor de la mitad de lo que hoy pagas.

Como parte de este lanzamiento tendrás:
- Tendrás visibilidad preferencial en el lanzamiento oficial.
- Acceso anticipado a nuevas funciones como agenda de reservas, venta de fotos y videos exclusivos.
- Uso gratuito a la plataforma y todas su funcionalidades durante este mes, si al cabo de este periodo quieres darte de baja lo podrás hacer sin ningún costo.
- Otros beneficios que te iremos contando en los próximos días.

Tu acceso para modificar tu espacio y agregar tu contenido es: https://topclass-girls.com/wp-admin

Usuario: {user}
Contraseña: 123456

Esta es una oportunidad única para consolidar tu imagen en el mercado y formar parte de una comunidad selecta y colaborativa.

¡Bienvenida a TopClass!
Donde tu talento se convierte en marca.",

"Hola {nombre},

Queremos aclararte algo importante respecto al link que recibiste: esto no es un anuncio público ni se está difundiendo por medios externos. Las fotos que usamos ya están publicadas en otras plataformas, y únicamente las estamos utilizando como referencia durante esta marcha blanca, para que cada una de ustedes pueda ver cómo quedaría su perfil en nuestra plataforma.

No se comparte ninguna imagen fuera del equipo interno ni con terceros. Nuestro objetivo es que puedas dar tu opinión y retroalimentación antes del lanzamiento oficial, para que todo quede lo mejor posible.

Si al final del proceso decides que no quieres continuar, retiraremos tu perfil sin ningún problema. Entendemos tu reacción de sorpresa pero te pedimos que nos des la oportunidad de que pruebes cómo funciona este nuevo concepto, que fue pensado especialmente para ti; estamos seguros de que te va a gustar.

Agradecemos mucho tu comprensión y colaboración. Cualquier pregunta o inquietud, responde a este mensaje sin dudarlo.

Muchas gracias,
TopClass
https://topclass-girls.com/ ",

"Hoy te escibo para recordarte el Acceso tu perfil de administración para que puedas ir probando (se hará un a inducción completa durante la próxima semana): 
URL: https://topclass-girls.com/wp-admin 
USER: {user} 
PASS: 123456
, si lo deseas puedes cambiar esta clave  y tu correo en tu perfil una vez que entres.",

"Hola {nombre}! hoy estaré disponible a las 21:00hrs vía Google Meet para resolver dudas y contarles más sobre TopClass. ¡Te espero!
[https://meet.google.com/eic-vpgi-whb?pli=1]. 
Acepta el mensaje para los links activos."
];

// Arreglo de status por tipo
$mensajes_answ = [
    'Red' => [
        "Hola {nombre}, gracias por tu mensaje. Estamos revisando tu caso y nos pondremos en contacto a la brevedad.",
        "Hola {nombre}, no te preocupes, tu situación está siendo evaluada y pronto recibirás toda la información necesaria."
    ],
    'Yellow' => [
        "Hola {nombre}, gracias por tu retroalimentación. Entendemos tus dudas y te pedimos unos momentos para mostrarte cómo funciona todo.",
        "Hola {nombre}, estamos analizando tu caso para asegurarnos de que todo quede claro."
    ],
    'Green' => [
        "Hola {nombre}, ¡Qué bueno que te gustó! Pronto recibirás nuevas actualizaciones y mensajes con más novedades.",
        "Hola {nombre}, nos alegra tu interés. Mantente atenta a los próximos mensajes para ver cómo avanzamos."
    ]
];



// Cargar JSON o inicializar desde modelos_base
$modelos_data = [];
if (file_exists($json_file)) {
    $modelos_data = json_decode(file_get_contents($json_file), true);
    if (!is_array($modelos_data)) $modelos_data = [];
}

//Borrar modelos eliminadas
/* foreach ($modelos_data as $modelo) {
    $user = $modelo['user'];
    if (!array_key_exists($user,$modelos_base)) {
        unset($modelos_data[$user]);
    }
} */
/* $usuarios_validos = array_column($modelos_base, 'user');

foreach ($modelos_data as $key => $modelo) {
    if (!in_array($modelo['user'], $usuarios_validos, true)) {
        unset($modelos_data[$key]);
    }
} */

// Asegurar que todos los modelos base estén en el JSON
foreach ($modelos_base as $modelo) {
    $user = $modelo['user'];
    if (!isset($modelos_data[$user])) {
        $modelos_data[$user] = [
            'nombre' => $modelo['nombre'],
            'telefono' => $modelo['telefono'],
            'status' => 'Red', // valor inicial
            'enviados' => [], // mensajes enviados
            'answ_enviados' => [] // mensajes enviados
        ];
    }
}

// Guardar JSON actualizado
file_put_contents($json_file, json_encode($modelos_data, JSON_PRETTY_PRINT), LOCK_EX);

// Guardar cambios por Ajax
if (isset($_POST['action']) && $_POST['action'] === 'update_modelo') {
    $user  = $_POST['user'] ?? '';
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';

    // Asegurar que el usuario exista en modelos_data
    if (!isset($modelos_data[$user])) {
        $modelos_data[$user] = [
            'status'   => 'Red',
            'enviados' => [],
            'answ_enviados' => [],
            'nota'     => ''
        ];
    }

    // Guardar según el campo
    if ($campo === 'status') {
        $modelos_data[$user]['status'] = $valor;
    } elseif (strpos($campo, 'enviado_') === 0) {
        $msg_key = substr($campo, 8);
        if ($valor) {
            $modelos_data[$user]['enviados'][$msg_key] = true;
        } else {
            unset($modelos_data[$user]['enviados'][$msg_key]);
        }
    } elseif (strpos($campo, 'answ_enviado_') === 0) {
        $msg_key = substr($campo, 13);
        if ($valor) {
            $modelos_data[$user]['answ_enviados'][$msg_key] = true;
        } else {
            unset($modelos_data[$user]['answ_enviados'][$msg_key]);
        }
    } elseif ($campo === 'nota') {
        $modelos_data[$user]['nota'] = $valor;
    }

    // Guardar JSON actualizado
    file_put_contents($json_file, json_encode($modelos_data, JSON_PRETTY_PRINT), LOCK_EX);

    echo json_encode(['status' => 'ok']);
    exit;
}
// Generar HTML con mensajes y botones de envío
$item_index = 1;
echo 
  "<div class='accordion' id='accordion-wa'>";

foreach ($modelos_data as $user => $modelo) {
    $nombre = $modelo['nombre'];
    $telefono = preg_replace('/[^0-9]/', '', $modelo['telefono']);
    $status = isset($modelo['status']) ? $modelo['status'] : 'Red';
    $item_open = 'false'; 
    $item_show = '';
    $item_collapse = 'collapsed';           
    if($item_index === 1){
        $item_open = 'true';
        $item_show = 'show';
        $item_collapse = '';       
    };

    echo 
      "<div class='accordion-item'>"; //accordion-item

    echo  
      "<h2 class='accordion-header'>" //accordion-header
    . "<button class='accordion-button d-flex justify-content-between align-items-center {$item_collapse}' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-{$telefono}' aria-expanded='{$item_open}' aria-controls='collapse-{$telefono}'>"
    . "<div class='d-flex align-items-center name'><span class='user-index'>{$item_index}.</span>{$nombre}</div>"
    . "<div class='d-flex align-items-center ms-auto me-3'>"
    . "<span id='badge-{$telefono}' class='me-2 badge'>" .count($modelo['enviados']). "</span>"
    . "<span id='dot-{$telefono}' class='dot " .strtolower($status). "'></span>"
    . "</div>" 
    .  "</button>" //accordion-button
    .  "</h2>"; //accordion-header

    echo  
       "<div id='collapse-{$telefono}' class='accordion-collapse collapse {$item_show}' data-bs-parent='#accordion-wa'>" //accordion-collapse
    .  "<div class='accordion-body px-0'>"; //accordion-body
 
    echo 
      "<div class='phone d-flex justify-content-end align-items-center me-3'>"
    . "<a href='https://wa.me/{$telefono}?text=%20' target='_blank' class='d-flex align-items-center'>"
    . split_phone($telefono)
    . "<i class='fa-brands fa-whatsapp ms-2 fs-3'></i>"
    . "</a>"
    . "</div>";

    echo 
      "<nav>"
    . "<div class='nav nav-tabs' id='nav-tab-{$telefono}' role='tablist'>"
    . "<button class='nav-link ms-3 active' id='nav-message-tab-{$telefono}' data-bs-toggle='tab' data-bs-target='#nav-message-{$telefono}' type='button' role='tab' aria-controls='nav-messages-{$telefono}' aria-selected='true'><i class='fa-solid fa-comment'></i></button>"
    . "<button class='nav-link' id='nav-answ-tab-{$telefono}' data-bs-toggle='tab' data-bs-target='#nav-answ-{$telefono}' type='button' role='tab' aria-controls='nav-answ-{$telefono}' aria-selected='false' tabindex='-1'><i class='fa-solid fa-reply'></i></button>"
    . "<button class='nav-link' id='nav-status-tab-{$telefono}' data-bs-toggle='tab' data-bs-target='#nav-status-{$telefono}' type='button' role='tab' aria-controls='nav-status-{$telefono}' aria-selected='false' tabindex='-1'><i class='fa-solid fa-traffic-light'></i></button>"
    . "</div>"
    . "</nav>";

    $enviados = isset($modelo['enviados']) ? $modelo['enviados'] : [];
    echo 
      "<div class='tab-content p-3' id='nav-tabContent'>"; //tab-content

    //tab-pane message
    echo 
      "<div class='nav-message tab-pane fade active show' id='nav-message-{$telefono}' role='tabpanel' aria-labelledby='nav-message-tab-{$telefono}'>"; 
    // Mensajes día
    foreach ($mensajes_dia as $idx => $msg) {
        $i = $idx + 1;
        $msg_text = str_replace(['{user}','{nombre}'], [$user,$nombre], $msg);
        $sent = isset($enviados["dia_{$i}"]) ? "✔ <a href='javascript:void(0)' class='unmark_msg ms-1' data-user='{$user}' data-msg='dia_{$i}' data-badge='badge-{$telefono}' data-actions='actions-{$telefono}-{$i}'>❌</a>" : '';
        $disable = $sent ? ' pe-none opacity-50' : '';
        $url = 'https://wa.me/' . $telefono . '?text=' . urlencode($msg_text);
        echo 
          "<div class='d-flex justify-content-between align-items-center message'>"
        . "<span>Mensaje día {$i}</span>"
        . "<span class='cd-actions ms-2 me-auto' id='actions-{$telefono}-{$i}'> {$sent}</span>"
        . "<a href='{$url}' target='_blank' class='msg_link btn-sender px-4 py-1 rounded-pill{$disable}' data-user='{$user}' data-msg='dia_{$i}' data-badge='badge-{$telefono}' data-actions='actions-{$telefono}-{$i}'>Enviar</a>"
        . "</div>";
    }
    echo 
      "</div>"; //tab-pane message

    $answ_enviados = isset($modelo['answ_enviados']) ? $modelo['answ_enviados'] : [];
    //tab-pane answ
    echo 
      "<div class='nav-answ tab-pane fade' id='nav-answ-{$telefono}' role='tabpanel' aria-labelledby='nav-answ-tab-{$telefono}'>"; 
    // Mensajes de respuesta
    foreach ($mensajes_answ as $color => $mensajes_color) {
        echo 
          "<div class='color-group'>";
        echo 
          "<span class='color-flag " .strtolower($color). "'></span>";
        foreach ($mensajes_color as $idx => $msg) {
            $i = $idx + 1;
            $msg_key = strtolower($color) . "_{$i}";
            $msg_text = str_replace(['{user}','{nombre}'], [$user,$nombre], $msg);
            $sent = isset($answ_enviados[$msg_key]) ? "✔ <a href='javascript:void(0)' class='unmark_msg ms-1' data-user='{$user}' data-msg='{$msg_key}' data-badge='badge-{$telefono}' data-actions='{$msg_key}-answ-actions-{$telefono}'>❌</a>" : '';
            $disable = $sent ? ' pe-none opacity-50' : '';
            $url = 'https://wa.me/' . $telefono . '?text=' . urlencode($msg_text);
            echo 
              "<div class='d-flex justify-content-between align-items-center message'>"
            . "<span>Mensaje {$i}</span>"
            . "<span class='cd-actions ms-2 me-auto' id='{$msg_key}-answ-actions-{$telefono}'> {$sent}</span>"
            . "<a href='{$url}' target='_blank' class='msg_link btn-sender px-4 py-1 rounded-pill{$disable}' data-user='{$user}' data-msg='{$msg_key}' data-actions='{$msg_key}-answ-actions-{$telefono}'>Enviar</a>"
            . "</div>";
        }
        echo 
          "</div>";
    }
    echo 
      "</div>"; //tab-pane answ

    //tab-pane status
    echo 
      "<div class='nav-status tab-pane fade' id='nav-status-{$telefono}' role='tabpanel' aria-labelledby='nav-status-tab-{$telefono}'>" 
    . "<div class='row'>"
    . "<div class='col-3 col-sm-2'>";
    $status_index = 1;
    foreach (['Red','Yellow','Green'] as $color) {
        $sel = ($status === $color) ? 'checked' : '';
        echo 
          "<div class='form-check status mb-3' id='color-selector-{$status_index}-{$telefono}'>"
        . "<input type='radio' value='{$color}' class='radio-status form-check-input' name='radioStatus-{$telefono}' id='radioStatus-{$status_index}-{$telefono}' data-user='{$user}' data-dot='dot-{$telefono}' {$sel}>"
        . "<label class='form-check-label color-flag " .strtolower($color). "' for='radioStatus-{$status_index}-{$telefono}'></label>";
        $status_index++;
        echo "</div>";
    }
    echo 
      "</div>"
    . "<div class='col-9 col-sm-10 ps-0 nota'>"
    . "<textarea class='nota-modelo form-control' id='nota-".$telefono."' data-user='".$user."' placeholder='Escribe una nota' rows='4'>".htmlspecialchars($modelo['nota'] ?? '')."</textarea>"
    . "</div>"
    . "</div>"
    . "</div>"; //tab-pane status

    echo 
      "</div>"; //tab-content

    echo 
      "</div>" //accordion-body
    . "</div>" //accordion-collapse
    . "</div>"; //accordion-item

    $item_index++;
}

echo 
  "</div>"; //accordion
?>
</div>
<script>
// Status radio
$('.radio-status').on('click', function() {
    const user = $(this).data('user');
    const dot = $(this).data('dot');
    const valor = $(this).val();

    $.post('', {
        action: 'update_modelo',
        user: user,
        campo: 'status',
        valor: valor
    }).done(function() {

        const headerDot = $('#' + dot);
        headerDot.removeClass('red yellow green').addClass(valor.toLowerCase());
    });
});

// Mensajes enviados
$('.msg_link').on('click', function() {

    const user = $(this).data('user');
    const badge = $(this).data('badge') || false;
    const actions = $(this).data('actions');
    const msg  = $(this).data('msg');
    const link = $(this);

    // Determinar si es mensaje o respuesta
    const isMessage = link.closest('.nav-message').length > 0;
    const campo_actual = isMessage ? `enviado_${msg}` : `answ_enviado_${msg}`;

    $.post('', {
        action: 'update_modelo',
        user: user,
        campo: campo_actual,
        valor: 1
    }).done(function() {
        link.addClass('pe-none opacity-50');
        link.prev().append(' ✔ <a href="javascript:void(0)" class="unmark_msg ms-1" data-user="'+user+'" data-msg="'+msg+'" data-badge="'+badge+'" data-actions="'+actions+'">❌</a>');
        if(isMessage && badge) {
            const headerBadge = $('#' + badge);      
            const currentVal = parseInt(headerBadge.text());
            headerBadge.text(currentVal + 1);
        }
    });
});

// Borrar check Mensajes enviados
$(document).on('click', '.unmark_msg', function() {
    const user = $(this).data('user');
    const badge = $(this).data('badge');
    const actions = $(this).data('actions');
    const msg  = $(this).data('msg');
    const link  = $(this);

    // Determinar si es mensaje o respuesta
    const isMessage = link.closest('.nav-message').length > 0;
    const campo_actual = isMessage ? `enviado_${msg}` : `answ_enviado_${msg}`;

    $.post('', {
        action: 'update_modelo',
        user: user,
        campo: campo_actual,
        valor: 0 // o eliminar del array
    }).done(function() {
         $('#' + actions).empty();
         $('.msg_link[data-actions="' + actions + '"]').removeClass('pe-none opacity-50');
        if(isMessage && badge) {
            const headerBadge = $('#' + badge);      
            const currentVal = parseInt(headerBadge.text());
            const newVal = currentVal > 0 ? currentVal - 1 : 0;
            headerBadge.text(newVal);
        }
    });
});


// Notas
$('.nota-modelo').on('blur', function() {
    const user  = $(this).data('user');
    const valor = $(this).val();

    $.post('', {
        action: 'update_modelo',
        user: user,
        campo: 'nota',
        valor: valor
    });
});

</script>
<script src="assets/js/bootstrap.bundle.min.js" ></script>
</body>
</html>




