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

$json = @file_get_contents('https://topclass-girls.com/wp-json/wp/v2/mensajes?_fields=title,content,categories&categories=79&date&order=asc');
if ($json === false) {
    echo 'Error cargando JSON';
} else {
    $mensajes_wp = json_decode($json, true);
}

$mensajes_dia = [];
foreach ($mensajes_wp as $msg) {
    $contenido = $msg['content']['rendered'] ?? '';

    // Reemplazar links: <a href="URL">texto</a> => URL
    $contenido = preg_replace('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>.*?<\/a>/i', '$1', $contenido);

    // Reemplazar <p ...> por doble salto codificado
    $contenido = preg_replace('/<p[^>]*>/i', "", $contenido);
    $contenido = preg_replace('/<\/p>/i', "\n", $contenido);
   

    // Reemplazar <br ...> por salto simple codificado
    $contenido = preg_replace('/<br\s*\/?>/i', '', $contenido);


    // Eliminar cualquier otro HTML restante
    $contenido = strip_tags($contenido);

    // Decodificar entidades HTML
    $contenido = html_entity_decode($contenido, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Limpiar saltos duplicados (opcional)
    $contenido = preg_replace("/\n{3,}/", "\n\n", trim($contenido));

    $mensajes_dia[] = [
        'titulo' => $msg['title']['rendered'] ?? 'Sin título',
        'contenido' => $contenido
    ];
}

// Endpoint de WP REST para los mensajes de respuesta
$url_endpoint = 'https://topclass-girls.com/wp-json/wp/v2/mensajes?_fields=title,content,categories&categories=76,77,78&date&order=asc';

// Traer JSON
$json = file_get_contents($url_endpoint);
$data = json_decode($json, true);

$mensajes_answ = [
    'Red' => [],
    'Yellow' => [],
    'Green' => []
];

// Mapear categorías de WP a colores
$cat_color_map = [
    76 => 'Red',    // Ej: mensajes Red
    77 => 'Yellow', // Ej: mensajes Yellow
    78 => 'Green'   // Ej: mensajes Green
];

if (is_array($data)) {
    foreach ($data as $item) {
        $cats = $item['categories'];
        $contenido = strip_tags($item['content']['rendered']); // eliminar <p>
        $titulo = $item['title']['rendered'] ?? 'Mensaje';

        foreach ($cats as $cat_id) {
            if (isset($cat_color_map[$cat_id])) {
                $color = $cat_color_map[$cat_id];
                $mensajes_answ[$color][] = [
                    $titulo => $contenido
                ];
            }
        }
    }
}

$url_endpoint = 'http://topclass-girls.com/wp-json/topclass/v1/modelos';
$json = file_get_contents($url_endpoint, false, $context);
$data = json_decode($json, true);

$modelos_base = [];
//$modelos_data = [];



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

// Cargar JSON o inicializar desde modelos_base


if (file_exists($json_file)) {
    $modelos_data = json_decode(file_get_contents($json_file), true);
    if (!is_array($modelos_data)) $modelos_data = [];
}

foreach ($modelos_data as $user => $modelo) {
    // Marcar como inactivo si ya no está en la lista oficial
    if (!in_array($user, array_column($modelos_base, 'user'), true)) {
      if($modelo['user'] != 'tester'){
        $modelo['activo'] = false;
      }
    } else {
        $modelo['activo'] = true;
    }
}
unset($modelo); // romper referencia


// Ahora aseguramos que todos los usuarios de modelos_base estén en modelos_data
foreach ($modelos_base as $modelo) {
    $user = $modelo['user'];
    if (!isset($modelos_data[$user])) {
        // Si no existe, lo agregamos con valores iniciales
        
        $modelos_data[$user] = [
            'nombre' => $modelo['nombre'],
            'telefono' => $modelo['telefono'],
            'status' => 'Yellow',
            'enviados' => [],
            'answ_enviados' => [],
            'activo' => true
        ];
    
    } else {
        // Si existe, actualizamos solo nombre y teléfono para mantener datos locales intactos
        if($modelos_data[$user] != 'tester'){
          $modelos_data[$user]['nombre'] = $modelo['nombre'];
          $modelos_data[$user]['telefono'] = $modelo['telefono'];
          $modelos_data[$user]['activo'] = true; // por si estaba inactivo antes
        }else{
          $modelos_data[$user]['telefono'] = $modelos_data[$user]['telefono'];
        }
    }
}

if (!isset($modelos_data['tester'])) {
      // Inyectar usuario de prueba
      $modelos_data['tester'] = [
          'nombre' => 'Tester',
          'user' => 'tester',
          'telefono' => '56911111111',
            'status' => 'Yellow',
            'enviados' => [],
            'answ_enviados' => [],
            'activo' => true
          
      ];
}
// Guardamos JSON actualizado
file_put_contents($json_file, json_encode($modelos_data, JSON_PRETTY_PRINT), LOCK_EX);


// Guardar cambios por Ajax
if (isset($_POST['action']) && $_POST['action'] === 'update_modelo') {
    $user  = $_POST['user'] ?? '';
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';

    // Asegurar que el usuario exista en modelos_data
    if (!isset($modelos_data[$user])) {
        $modelos_data[$user] = [
            'status'   => 'Yellow',
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

// Guardar cambios de  celu de tester por Ajax
if (isset($_POST['action']) && $_POST['action'] === 'update_and_split') {
  $user  = $_POST['user'] ?? '';
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';

    // Asegurar que el usuario exista en modelos_data
    if (!isset($modelos_data[$user])) {
        $modelos_data[$user] = [
            'status'   => 'Yellow',
            'enviados' => [],
            'answ_enviados' => [],
            'nota'     => ''
        ];
    }

    $modelos_data[$user]['telefono'] = $valor;

    // Guardar JSON actualizado
    file_put_contents($json_file, json_encode($modelos_data, JSON_PRETTY_PRINT), LOCK_EX);
    echo split_phone($valor);
    exit;
}
// Filtrar solo los activos para la visualización
$visual = array_filter($modelos_data, fn($m) => $m['activo']);

// Orden alfabético solo para visual
function sortAlphabetically($a, $b) {
    return strcmp($a['nombre'], $b['nombre']);
}
uasort($visual, 'sortAlphabetically');

// Mover Tester al primer lugar después del sort
if (isset($visual['tester'])) {
    $tester = $visual['tester'];
    unset($visual['tester']);
    $visual = ['tester' => $tester] + $visual; // ahora visual empieza con tester
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="height=device-height, width=device-width, minimum-scale=1">
<link rel="icon" href="./assets/img/favicon-100x100.png" sizes="32x32" type="image/png">
<link rel="icon" href="./assets/img/favicon-300x300.png" sizes="192x192" type="image/png">
<link rel="apple-touch-icon" href="./assets/img/favicon-300x300.png" type="image/png">
<meta name="msapplication-TileImage" content="./assets/img/favicon-300x300.png" type="image/png">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<link href="assets/css/main.css" rel="stylesheet" />
<script src="assets/js/color-modes.js"></script>
<script src="assets/js/fontawesome.js"></script>
<script src="assets/js/jquery.min.js"></script>
</head>
<body>

<div class="wa-box">

<div class="brand d-flex align-items-center justify-content-center" href="#">
  <img src="./assets/img/iso-TC.svg" alt="TC Logo" class="logo">
  <sup>©</sup>       
  <p>WA Sender</p>
</div>
<?php
$item_index = 1;
echo 
  "<div class='accordion' id='accordion-wa'>";

foreach ($visual as $user => $modelo) {
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

    $edit_phone = ($user == 'tester')? '<span class="d-none" id="edit-phone-box"><input type="text" class="form-control" value="' .$telefono. '" name="edit-phone" id="edit-phone" style="width: 120px; border: 0; font-size: .875rem; line-height: 1" data-user="tester" data-phone="phone-number-' .$telefono. '"/><i class="fa-brands fa-whatsapp ms-2 fs-3" style="color: #6ecdff"></i></span>' : '';
    $site_link = ($user != 'tester')? '<a href="https://topclass-girls.com/modelo/' . $user . '" class="site-link" target="_blank"><span class="user-index">' . $item_index . '. </span>' .$nombre . '<i class="fa-solid fa-arrow-up-right-from-square ms-2"></i></a>' : '<a href="#" class="tester-edit" id="tester-edit" data-phone="phone-number-' .$telefono. '"><span class="user-index">' . $item_index . '. </span>' .$nombre. '<i class="fa-solid fa-pen ms-2"></i></a>';
    echo 
      "<div class='accordion-item'>"; //accordion-item

    echo  
      "<h2 class='accordion-header'>" //accordion-header
    . "<div class='accordion-button d-flex justify-content-between align-items-center {$item_collapse}' type='button' data-bs-toggle='collapse' data-bs-target='#collapse-{$telefono}' aria-expanded='{$item_open}' aria-controls='collapse-{$telefono}'>"
    . "<div class='d-flex align-items-center ms-auto me-3'>"
    . "<span id='badge-{$telefono}' class='me-2 badge'>" .count($modelo['enviados']). "</span>"
    . "<span id='dot-{$telefono}' class='dot " .strtolower($status). "'></span>"
    . "</div>" 
    .  "</div>" //accordion-button
    . "<div class='d-flex align-items-center name'>{$site_link}</div>"
    .  "</h2>"; //accordion-header

    echo  
       "<div id='collapse-{$telefono}' class='accordion-collapse collapse {$item_show}' data-bs-parent='#accordion-wa'>" //accordion-collapse
    .  "<div class='accordion-body px-0'>"; //accordion-body
 
    echo 
      "<div class='phone d-flex justify-content-end align-items-center me-3'>"
    . $edit_phone
    . "<a href='https://wa.me/{$telefono}?text=%20' target='_blank' class='d-flex align-items-center' id='phone-number-{$telefono}'>"
    . "<span>" .split_phone($telefono). "</span>"
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

    
  
  // Dibujar en el frontend
  $enviados = isset($modelo['enviados']) ? $modelo['enviados'] : [];
  echo "<div class='tab-content p-3' id='nav-tabContent'>";

  echo "<div class='nav-message tab-pane fade active show' id='nav-message-{$telefono}' role='tabpanel' aria-labelledby='nav-message-tab-{$telefono}'>";

  foreach ($mensajes_dia as $idx => $msg) {
      $i = $idx + 1;
      $msg_text = str_replace(['{user}','{nombre}'], [$user,$nombre], $msg['contenido']);
      $sent = isset($enviados["dia_{$i}_{$telefono}"]) ? "<span>✔</span> <a href='javascript:void(0)' class='unmark_msg ms-1 fs-5' data-user='{$user}' data-msg='dia_{$i}_{$telefono}' data-badge='badge-{$telefono}' data-actions='actions-{$telefono}-{$i}' style='color: #c10000ff!important; line-height: 1;'>✘</a>" : '';
      $disable = $sent ? ' pe-none opacity-50' : '';
      $url = 'https://api.whatsapp.com/send?phone=' . $telefono . '&text=' . urlencode($msg_text);

      echo "<div class='d-flex justify-content-between align-items-center message'>"
          . "<span class='msg_title{$disable}' data-user='{$user}' data-msg='dia_{$i}_{$telefono}' data-badge='badge-{$telefono}' data-actions='actions-{$telefono}-{$i}' style='cursor: pointer'>&#x2022; {$msg['titulo']}</span>"
          . "<span class='cd-actions d-flex align-items-center ms-2 me-auto' id='actions-{$telefono}-{$i}'> {$sent}</span>"
          . "<a href='{$url}' target='_blank' class='msg_link btn-sender px-4 py-1 rounded-pill{$disable}' data-user='{$user}' data-msg='dia_{$i}_{$telefono}' data-badge='badge-{$telefono}' data-actions='actions-{$telefono}-{$i}'>Enviar <i class='fa-solid fa-arrow-right'></i></a>"
          . "</div>";
  }

  echo "</div>"; // nav-message

  $answ_enviados = isset($modelo['answ_enviados']) ? $modelo['answ_enviados'] : [];
  //tab-pane answ
  echo "<div class='nav-answ tab-pane fade' id='nav-answ-{$telefono}' role='tabpanel' aria-labelledby='nav-answ-tab-{$telefono}'>"; 

  // Mensajes de respuesta
  foreach ($mensajes_answ as $color => $mensajes_color) {
      echo "<div class='color-group'>";
      echo "<span class='color-flag mb-1 " . strtolower($color) . "'></span>";

      foreach ($mensajes_color as $idx => $msg_array) {
          $i = $idx + 1;
          $titulo = key($msg_array);           // Título del mensaje
          $msg_text = current($msg_array);     // Contenido del mensaje
          $msg_text = str_replace(['{user}','{nombre}'], [$user, $nombre], $msg_text);
          $msg_key = strtolower($color) . "_{$i}_{$telefono}";

          $sent = isset($answ_enviados[$msg_key]) 
              ? "<span>✔</span> <a href='javascript:void(0)' class='unmark_msg ms-1 fs-5' data-user='{$user}' data-msg='{$msg_key}' data-badge='badge-{$telefono}' data-actions='{$msg_key}-answ-actions-{$telefono}' style='color: #c10000ff!important; line-height: 1;'>✘</a>" 
              : '';
          $disable = $sent ? ' pe-none opacity-50' : '';
          $url = 'https://api.whatsapp.com/send?phone=' . $telefono . '&text=' . urlencode($msg_text);

          echo "<div class='d-flex justify-content-between align-items-center message'>"
            . "<span class='msg_title{$disable}'  data-user='{$user}' data-msg='{$msg_key}' data-actions='{$msg_key}-answ-actions-{$telefono}' style='cursor: pointer'>&#x2022; {$titulo}</span>"   // <-- aquí va el título en lugar de "Mensaje 1"
            . "<span class='cd-actions d-flex align-items-center ms-2 me-auto' id='{$msg_key}-answ-actions-{$telefono}'> {$sent}</span>"
            . "<a href='{$url}' target='_blank' class='msg_link btn-sender px-4 py-1 rounded-pill{$disable}' data-user='{$user}' data-msg='{$msg_key}' data-actions='{$msg_key}-answ-actions-{$telefono}'>Enviar <i class='fa-solid fa-arrow-right'></i></a>"
            . "</div>";
      }

      echo "</div>";
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
$('.msg_link, .msg_title').on('click', function() {

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
        $('.msg_link[data-actions="' + actions + '"], .msg_title[data-actions="' + actions + '"]').addClass('pe-none opacity-50');
        $('#' + actions).append('<span>✔</span> <a href="javascript:void(0)" class="unmark_msg ms-1 fs-5" data-user="'+user+'" data-msg="'+msg+'" data-badge="'+badge+'" data-actions="'+actions+'" style="color: #c10000ff!important; line-height: 1;">✘</a>');
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
         $('.msg_link[data-actions="' + actions + '"], .msg_title[data-actions="' + actions + '"]').removeClass('pe-none opacity-50');
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

$('#edit-phone').on('blur', function() {
    const user  = $(this).data('user');
    const phone  = $(this).data('phone');
    const valor = $(this).val();

    $.post('', {
        action: 'update_and_split',
        user: user,
        campo: 'telefono',
        valor: valor
    }).done(function(response){ 
      $('#' + phone + ' span').text(response);
        $('#edit-phone-box').removeClass('d-flex align-items-center').addClass('d-none');
        $('#' + phone).removeClass('d-none');
    })      
});

$('#tester-edit').on('click', function(e) {
  e.preventDefault(); // 
  const phone  = $(this).data('phone');
  $('#edit-phone-box').removeClass('d-none').addClass('d-flex align-items-center');
  $('#' + phone).addClass('d-none');
});
</script>
<script src="assets/js/bootstrap.bundle.min.js" ></script>
</body>
</html>