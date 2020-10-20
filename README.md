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
