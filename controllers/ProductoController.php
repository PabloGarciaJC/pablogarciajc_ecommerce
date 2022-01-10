<?php

require_once 'model/productos.php';

class ProductoController
{
  public function crear()
  {
    //Acceso Usuario Registrado a esta Pagina
    Utils::accesoUsuarioRegistrado();

    //Obtengo Ususario en el Banner
    $usuario = Utils::obtenerUsuario();

    //Imprimo Lista Categoria   
    $categoria = Utils::listaCategorias();

    //Obtengo Categorias en la Barra de Navegacion
    $categoriaBarraNavegacion = Utils::listaCategorias();

    if (isset($_GET['id'])) {
      $obtenerProductosPorId = Utils::obtenerProductosPorId($_GET['id']);
    }

    require_once 'views/layout/header.php';
    require_once 'views/layout/banner.php';
    require_once 'views/layout/nav.php';
    require_once 'views/layout/search.php';
    require_once 'views/layout/sidebarAdministrativo.php';
    require_once 'views/producto/crear.php';
    require_once 'views/layout/footer.php';
  }

  public function guardar()
  {
    //POST
    $nombreProducto = isset($_POST['nombreProducto']) ? $_POST['nombreProducto'] : false;
    $idProducto = isset($_POST['idProducto']) ? $_POST['idProducto'] : false;
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : false;
    $precioProducto = isset($_POST['precioProducto']) ? $_POST['precioProducto'] : false;
    $stockProducto = isset($_POST['stockProducto']) ? $_POST['stockProducto'] : false;
    $ofertaProducto = isset($_POST['ofertaProducto']) ? $_POST['ofertaProducto'] : false;
    $marcaProducto = isset($_POST['marcaProducto']) ? $_POST['marcaProducto'] : false;
    $memoriaRamProducto = isset($_POST['memoriaRamProducto']) ? $_POST['memoriaRamProducto'] : false;
    $descripcionProducto = isset($_POST['descripcionProducto']) ? $_POST['descripcionProducto'] : false;

    //FILES
    $nombreArchivo = isset($_FILES['guardarImagenProducto']['name']) ? $_FILES['guardarImagenProducto']['name'] : false;
    $tipoArchivo = isset($_FILES['guardarImagenProducto']['type']) ? $_FILES['guardarImagenProducto']['type'] : false;
    $rutaTemporal = isset($_FILES['guardarImagenProducto']['tmp_name']) ? $_FILES['guardarImagenProducto']['tmp_name'] : false;
    $pesoArchivo = isset($_FILES['guardarImagenProducto']['size']) ? $_FILES['guardarImagenProducto']['size'] : false;
    $sizeArchivoMax = "1048576"; // 1 MB expresado en bytes //1048576

    //validacion
    $errores = array();

    if (empty($nombreProducto)) {
      $errores["nombreProducto"] = "Debe completar Producto";
    }

    if (empty($precioProducto)) {
      $errores["precioProducto"] = "Debe completar Precio";
    }

    if (empty($stockProducto)) {
      $errores["stockProducto"] = "Debe completar Stock";
    }

    if (empty($ofertaProducto)) {
      $errores["ofertaProducto"] = "Debe completar Oferta";
    }

    if (empty($marcaProducto)) {
      $errores["marcaProducto"] = "Debe completar Marca";
    }

    if (empty($descripcionProducto)) {
      $errores["descripcionProducto"] = "Debe completar Descripcion";
    }

    if ($tipoArchivo == "image/gif") {
      $errores["tipoArchivo"] = "Tipo de Archivo No Valido";
    }

    // Crear Fichero donde guardo la imagenes en el Proyecto.
    if (!is_dir('uploads/images/productos/')) {
      mkdir('uploads/images/productos/', 0777, true);
    }
    // Muevo la imagen en el fichero que se creo anteriormente.
    move_uploaded_file($rutaTemporal, 'uploads/images/productos/' . $nombreArchivo);

    //Instancio y Seteo indiferentemene de la Condicion
    $producto = new Productos();
    $producto->setNombre($nombreProducto);
    $producto->setIdCategoria($categoria);
    $producto->setPrecio($precioProducto);
    $producto->setStock($stockProducto);
    $producto->setOferta($ofertaProducto);
    $producto->setMarca($marcaProducto);
    $producto->setMemoriaRam($memoriaRamProducto);
    $producto->setDescripcion($descripcionProducto);
    $producto->setImagen($nombreArchivo);

    if ($rutaTemporal) {
      if ($idProducto) { //'Existe' . $idProducto, Si Existe Ruta Temporal, Actualizar PRODUCTO';  
        // Obtengo URL Guardada en la Base de Datos y en URL Fichero Actual
        $mostrarImagen = Utils::obtenerProductosPorId($idProducto);
        $ruta = 'uploads/images/productos/' . $mostrarImagen->imagen;
        if ($mostrarImagen->imagen != $nombreArchivo && is_file($ruta)) {
          //Borra la imagen anterior para que no quede Guardada en el Fichero
          unlink($ruta);
        }
        $producto->setId($idProducto);
        if (count($errores) == 0) {
          $producto->actualizar();
          echo '1';
        }
      } else { // 'NO Existe' . $idProducto, Si Existe Ruta Temporal, CREAR PRODUCTO'; 
        if (count($errores) == 0) {
          $producto->crear();
          echo '1';
        }
      }
    } else {
      if ($idProducto) { //'Existe' . $idProducto, NO Existe Ruta Temporal, Actualizar PRODUCTO';          
        $producto->setId($idProducto);
        $mostrarImagen = Utils::obtenerProductosPorId($idProducto);
        $producto->setImagen($mostrarImagen->imagen);
        if (count($errores) == 0) {
          $producto->actualizar();
          echo '1';
        }
      } else { // 'NO Existe' . $idProducto, NO Existe Ruta Temporal, CREAR PRODUCTO';        
        if (count($errores) == 0) {
          $producto->crear();
          echo '1';
        }
      }
    }
  }

  public function listar()
  {
    //Acceso Usuario Registrado a esta Pagina
    Utils::accesoUsuarioRegistrado();

    //Obtengo Ususario en el Banner
    $usuario = Utils::obtenerUsuario();

    //Obtengo Categorias en la Barra de Navegacion    
    $categoriaBarraNavegacion = Utils::listaCategorias();

    // Paginador 1: Extraer el Total d Registros y Cantidad de Registro a Mostrar
    $obtenerRegistros = Utils::obtenerRegistrosTotales();
    $totalRegistrosBd = $obtenerRegistros->registros_totales;
    $mostrarRegistros = 5;

    // Paginador 2: Valido Si PAG Existe en GET 
    empty($_GET['pag']) ? $pagina = 1 : $pagina = $_GET['pag'];

    // Paginador 3: Obtener Ultimo Registro de la Pagina y mostrarNumerosdePaginas
    $ultimoRegistro = ($pagina - 1) * $mostrarRegistros;
    $mostrarNumerosdePaginas = ceil($totalRegistrosBd / $mostrarRegistros);

    // Imprimo Lista y Capturo el Buscador de Productos 
    isset($_GET['buscador']) ? $productos = Utils::obtenerProductosyBuscador($_GET['buscador']) : $productos = Utils::obtenerProductosYPaginador($ultimoRegistro, $mostrarRegistros);

    require_once 'views/layout/header.php';
    require_once 'views/layout/banner.php';
    require_once 'views/layout/nav.php';
    require_once 'views/layout/search.php';
    require_once 'views/layout/sidebarAdministrativo.php';
    require_once 'views/producto/listar.php';
    require_once 'views/layout/footer.php';
  }

  public function buscardor()
  {
    $buscando = $_POST['buscar'];
    header("location: " . base_url . "Producto/listar&buscador=" . $buscando);
  }

  public function eliminar()
  {
    $id = $_POST['id'];
    $producto = new Productos();
    $producto->setId($id);
    $eliminado = $producto->eliminar();
    if ($eliminado) {
      echo 1;
    }
  }

  public function mostrar()
  {
    // Obtengo el Id de Producto por Categoria
    $idProductoCategoria = isset($_GET['producto']) ? $_GET['producto'] : false;

    // Obtengo Usuario en el Banner
    $usuario = Utils::obtenerUsuario();

    // Obtengo Categorias en la Barra de Navegacion
    $categoriaBarraNavegacion = Utils::listaCategorias();

    // Obtengo los Productos por Categoria Id
    $mostrarProductoPorCategoria = Utils::obtenerProductosPorCategoriaId($idProductoCategoria);

    // Obtengo Marca Sin Repetir en el Sidebar
    $mostrarMarcaSinRepetirSidebar = Utils::mostrarMarcaSinRepetirSidebar($idProductoCategoria);

    // Extraer Registros de la Base de Datos con Fetch_Object
    $obtenerProductoPorCategoriaId = Utils::extraerRegistros($mostrarProductoPorCategoria);


    require_once 'views/layout/header.php';
    require_once 'views/layout/banner.php';
    require_once 'views/layout/nav.php';
    require_once 'views/layout/search.php';
    require_once 'views/producto/mostrarProducto.php';
    require_once 'views/layout/footer.php';
  }

  public function mostrarPorCategoriaId()
  {
    $buscadorProducto = isset($_POST['buscadorProducto']) ? $_POST['buscadorProducto'] : false;
    $productosIdCategoria = isset($_POST['productoIdCategoria']) ? $_POST['productoIdCategoria'] : false;

    // Obtengo los Productos por Categoria Id Extrallendo
    // $productoIdCategorias = Utils::obtenerProductosPorCategoriaId($productosIdCategoria);

    // Extraer el Total de Registros por Categoria Id O por el Buscador
    $obtenerRegistrosTotales = $buscadorProducto == '' ? Utils::conteoRegistrosCategoriaId($productosIdCategoria) : Utils::conteoBuscadorRegistrosCategoriaId($buscadorProducto, $productosIdCategoria);
    $conteoRegistroProductoId = $obtenerRegistrosTotales->registros_totales;

    // Numero de Registro que voy a mostrar en un Div
    $mostrarRegistros = 3;

    // Total de Div que se van a Crear
    $crearRegistroPorDiv = ceil($conteoRegistroProductoId / $mostrarRegistros);

    // Creo los Div y dentro de cada Uno de ellos o el Muestros los Registros de la Base de Datos Limitados a 3
    for ($conteoIdProducto = 0; $conteoIdProducto < $crearRegistroPorDiv; $conteoIdProducto++) {

      echo '<div class="product-sec1 px-sm-4 px-3 py-sm-5  py-3 mb-4">';

      $ultimoRegistro = $mostrarRegistros * $conteoIdProducto;

      //Mostrar Producto Todos O Por el Buscador Creando Div y Registros
      $buscadorProducto == '' ? $mostrarProductos = Utils::obtenerProductoPorCategoriaId($ultimoRegistro, $mostrarRegistros, $productosIdCategoria) : $mostrarProductos = Utils::mostrarProductosBuscadorLimitar($buscadorProducto, $ultimoRegistro, $mostrarRegistros, $productosIdCategoria);

      echo '<div class="row">';
      while ($mostrarProducto = $mostrarProductos->fetch_object()) {
        echo '<div class="col-md-4 product-men mt-md-0 mt-5">';
        echo '<div class="men-pro-item simpleCart_shelfItem">';
        echo '<div class="men-thumb-item text-center">';
        echo '<img src="' . base_url . 'assets/images/' . $mostrarProducto->imagen . '" alt="">';
        echo '<div class="men-cart-pro">';
        echo '</div>';
        echo '<span class="product-new-top">Newe</span>';
        echo '</div>';
        echo '<div class="item-info-product text-center border-top mt-4">';
        echo '<h4 class="pt-1">';
        echo '<a href="single.html">' . $mostrarProducto->nombre . '</a>';
        echo '</h4>';
        echo '<div class="info-product-price my-2">';
        echo '<span class="item_price">' . $mostrarProducto->precio . '</span> <del>' . $mostrarProducto->oferta . '</del>';
        echo '</div>';
        echo '<div class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">';
        echo '<form action="#" method="post">';
        echo '<fieldset>';
        echo '<input type="hidden" name="cmd" value="_cart" />';
        echo '<input type="hidden" name="add" value="1" />';
        echo '<input type="hidden" name="business" value=" " />';
        echo '<input type="hidden" name="item_name" value="Apple iPhone X" />';
        echo '<input type="hidden" name="amount" value="280.00" />';
        echo '<input type="hidden" name="discount_amount" value="1.00" />';
        echo '<input type="hidden" name="currency_code" value="USD" />';
        echo '<input type="hidden" name="return" value=" " />';
        echo '<input type="hidden" name="cancel_return" value=" " />';
        echo '<input type="submit" name="submit" value="Add to cart" class="button btn" />';
        echo '</fieldset>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
      };
      echo '</div>';
      echo '</div>';
      echo '</div>';
    };
  }

  public function marca()
  {

    //Obtengo los Valores por POST
    $productoByIdCategoria = isset($_POST['productoByIdCategoria']) ? $_POST['productoByIdCategoria'] : false;
    $arrayMarcaCheckbox = isset($_POST["arrayMarca"]) ? json_decode($_POST["arrayMarca"]) : false;
    $arrayMemoriaRamCheckbox = isset($_POST["arrayMemoriaRam"]) ? json_decode($_POST["arrayMemoriaRam"]) : false;
    $arrayPrecioCheckbox = isset($_POST["arrayPrecio"]) ? json_decode($_POST["arrayPrecio"]) : false;


    // Counteo de checkbox selecionados
    $conteoArrayMarca = count($arrayMarcaCheckbox);
    $conteoMemoriaRam = count($arrayMemoriaRamCheckbox);
    $conteoPrecio = count($arrayPrecioCheckbox);

    // Numero de Registro que voy a mostrar en un Div
    $mostrarRegistros = 3;

    // Obtengo Valores de Checkbox Selecionado
    $consultaFragmentadasCheckbox =  Utils::consultaFragmentadasCheckbox($arrayMarcaCheckbox, $conteoArrayMarca, $productoByIdCategoria, $arrayMemoriaRamCheckbox, $conteoMemoriaRam, $arrayPrecioCheckbox, $conteoPrecio);

  
      var_dump($consultaFragmentadasCheckbox );


    // while ($ExtraerRegistrosCheckbox = Utils::extraerRegistros($consultaFragmentadasCheckbox)){

    //   var_dump($ExtraerRegistrosCheckbox->nombre);

    // };


    // $tesB = Utils::consultaTes($tesA);









    // $conteoRegistroProductoId = 9;

    // // Numero de Div que se van a Crear por Consulta
    // $crearRegistroPorDiv = ceil($conteoRegistroProductoId / $mostrarRegistros);


    // // Creo los Div Por Consulta
    // for ($conteoIdProducto = 0; $conteoIdProducto < $crearRegistroPorDiv; $conteoIdProducto++) {

    //   $ultimoRegistro = $mostrarRegistros * $conteoIdProducto;


    // Muestro Los Registros que van en cada div Creado
    // foreach ($arrayCheckbox as $listaCheckbox) {
    //   $mostrarProductos = Utils::mostrarProductosBuscadorLimitar($listaCheckbox, $ultimoRegistro, $mostrarRegistros, $productoByIdCategoria);
    // }


    // };







    // $productoIdCategorias = Utils::obtenerProductosPorCategoriaId($productoByIdCategoria);
    // var_dump($productoIdCategorias);


    // Extraer el Conteo de Registros por Categoria Id O Por Checkbox
    // foreach ($array as $valor) {
    //   $obtenerRegistrosTotales = Utils::conteoBuscadorRegistrosCategoriaIdFiltros($valor, $productoByIdCategoria);
    // }
    // $conteoRegistroProductoId = $obtenerRegistrosTotales->registros_totales;

    // Numero de Registro que voy a mostrar en un Div
    // $mostrarRegistros = 3;

    // // Total de Div que se van a Crear.
    // $crearRegistroPorDiv = ceil($conteoRegistroProductoId / $mostrarRegistros);
    // var_dump($crearRegistroPorDiv);

    // // Creo los Div y dentro de cada Uno, los Registros de la Base de Datos
    // for ($conteoIdProducto = 0; $conteoIdProducto < $crearRegistroPorDiv; $conteoIdProducto++) {

    //   echo '<div class="product-sec1 px-sm-4 px-3 py-sm-5  py-3 mb-4">';

    //   $ultimoRegistro = $mostrarRegistros * $conteoIdProducto;

    //   //Mostrar Producto Todos O Por el Buscador Creando Div y Registros
    //   foreach ($array as $valor) {
    //     $mostrarProductos = Utils::mostrarProductosBuscadorLimitar($valor, $ultimoRegistro, $mostrarRegistros, $productoByIdCategoria);
    //   }

    //   echo '<div class="row">';
    //   while ($mostrarProducto = $mostrarProductos->fetch_object()) {
    //     echo '<div class="col-md-4 product-men mt-md-0 mt-5">';
    //     echo '<div class="men-pro-item simpleCart_shelfItem">';
    //     echo '<div class="men-thumb-item text-center">';
    //     echo '<img src="' . base_url . 'assets/images/' . $mostrarProducto->imagen . '" alt="">';
    //     echo '<div class="men-cart-pro">';
    //     echo '</div>';
    //     echo '<span class="product-new-top">Newe</span>';
    //     echo '</div>';
    //     echo '<div class="item-info-product text-center border-top mt-4">';
    //     echo '<h4 class="pt-1">';
    //     echo '<a href="single.html">' . $mostrarProducto->nombre . '</a>';
    //     echo '</h4>';
    //     echo '<div class="info-product-price my-2">';
    //     echo '<span class="item_price">' . $mostrarProducto->precio . '</span> <del>' . $mostrarProducto->oferta . '</del>';
    //     echo '</div>';
    //     echo '<div class="snipcart-details top_brand_home_details item_add single-item hvr-outline-out">';
    //     echo '<form action="#" method="post">';
    //     echo '<fieldset>';
    //     echo '<input type="hidden" name="cmd" value="_cart" />';
    //     echo '<input type="hidden" name="add" value="1" />';
    //     echo '<input type="hidden" name="business" value=" " />';
    //     echo '<input type="hidden" name="item_name" value="Apple iPhone X" />';
    //     echo '<input type="hidden" name="amount" value="280.00" />';
    //     echo '<input type="hidden" name="discount_amount" value="1.00" />';
    //     echo '<input type="hidden" name="currency_code" value="USD" />';
    //     echo '<input type="hidden" name="return" value=" " />';
    //     echo '<input type="hidden" name="cancel_return" value=" " />';
    //     echo '<input type="submit" name="submit" value="Add to cart" class="button btn" />';
    //     echo '</fieldset>';
    //     echo '</form>';
    //     echo '</div>';
    //     echo '</div>';
    //     echo '</div>';
    //     echo '</div>';
    //   };
    //   echo '</div>';
    //   echo '</div>';
    //   echo '</div>';
    // };
  }
}
