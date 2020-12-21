# Plantilla MVC con formulario

La función principal de este proyecto es almacenar la ultima versión de los archivos generales para el funcionamiento de mi plataforma **MVC**.
Adicionalmente, también contiene la funcionalidad del formulario **MVC** implementado con la plantilla **AdminLTE 3**

# Versiones

> Las versiones se separan en cuatro casillas separadas por un punto (0.0.0.0). Las primeras dos casillas indican la versión de la plataforma **MVC**. Las últimas dos casillas indican la versión del proyecto, para este proyecto quizá es redundate, pero será mas útil para otros proyectos.

## Versión 0.0.0.0

Aunque esta es la versión inicial, en realidad no es así, mas bien me refiero a que esta es la primer versión registrada en github

## Versión 0.1.1.4

En esta versión se toman los ajustes implementadios en el sistema taller_csp. Es 0.1.1.4 debido a que se esta considerando el consecutivo que se llevaba en la versión anterior

## Versión 0.2.1.5

> Cambios en la plataforma MVC

- Nueva clase modelo **ALTE3HTML**
- Nueva clase modelo **CatEstado**
- **ControladorBase**. Nuevas funciones **getArrHTMLTag**, **getHTMLTag**, **redireccionaError** y **redireccionaErrorAccion**
- **ModeloBase**. Optimización en las funciones **setArrTbl** y **setArrReg**
- **CatCuestModulo**. Adecuaciones a la optimización de **ModeloBase**
- **CuestCmpDef**. Adecuaciones a la optimización de **ModeloBase**
- **AdjuntoControl**. Implementación del llamado a error mediante **redireccionaError**
- **CuestionarioControl**. Implementación del llamado a error mediante **redireccionaError** y **redireccionaErrorAccion** 

> Cambios en el proyecto

- Nuevo controlador **PruebasControl**
- Nueva vista **Pruebas**

## Versión 0.3.1.5

> Cambios en la plataforma MVC

- Se sustituyó la función url_controlador por define_controlador
- Clase modelo **AlertaGenerica**. Se corrigió el llamado a las librerias **Bootstrap**
- Clase modelo **CampoAtributo**. Se eliminó clase con archivo contenedor **CampoAtributo.class.php**.

## Versión 1.0.1.5

> Cambios en la plataforma MVC

- Clase modelo **Log**. Se agregó columna **remote_addr**
- Archivo vista **DropdownUserMenu.php**. Se eliminó.
- Archivo vista **EnAside.php**. Se eliminó.
- Archivo vista **EnMainSidebar.php**. Se eliminó.
- Archivo vista **EnNavbar.php**. Se eliminó.
- Archivo vista **EnNavbarTopNav.php**. Se eliminó.
- Archivo vista **FrmCeroMML.php**. Se eliminó.
- Archivo vista **ScriptMainSB.php**. Se eliminó.

## Versión 1.1.1.5

> Cambios en la plataforma MVC

- Archivo **index.php**. Se quitó el uso de la función redireccionar para errores de ruta inválida, en su lugar, se puso el error directo. 
- **Controladores**. Por cuestiones de claridad, se cambió el nombre de la función **setMostrarVista** por **defineVista**.
- **ControladorBase**. Se agregaron las variables **con_menu_lateral_fijo**, **llamado_por_ajax** y **usar_lib_toastr**. Variables que faltó agregar del proyecto **taller_csp** 
- Archivo vista **EnHead.php**. Se importó del proyecto **taller_csp**, debido a que esa era la última versión y faltó agregarlo en la integración en el proyecto **mvc_form**
- Archivo vista **Scripts.php**. Se importó del proyecto **taller_csp**, debido a que esa era la última versión y faltó agregarlo en la integración en el proyecto **mvc_form**
- Definición de variable global **COLOR_ACENTUAR** en archivo **config**

## Versión 1.1.1.6

> Cambios en la plataforma MVC

- Homologación de archivos con los del proyecto **siap_igei**

## Versión 1.1.1.7

> Cambios en la plataforma MVC

- Se metió pantalla para poder convertir el arreglo json **arr_cmp_atrib** generado por la clase modelo **FormularioALTE3** en una tabla
- Nueva clase **TblCamposControl**.
- Nuevo archivo vista **TblCampos.php**.