var FormaC01Mod1 = function(){
	function ocu_div_org(){
		var v_ocultar = (getValueForm('persona_tipo',true)==1)? true : false;
		ocultar_secciones(v_ocultar, "#div_org");
		bloqueaCampos(v_ocultar, ['org_nombre','org_razon_soc']);
	}
	/**
	 * Acciones que se ejecutan dependiendo del valor en el el campo ubica_estado
	 */
	function dep_ubica_estado(){
		v_cat_estado_id = getValueForm('ubica_estado',false);
		
		$.ajax({
			url:"index.php?controlador=catalogo&accion=imprime_municipios",
			data:"cat_estado_id="+v_cat_estado_id,
			cache:false,
			dataType:"html",
			success:function(result){
				$("#ubica_municipio").html(result);
				$("#cobertura_municipio").html(result);
			},
			error:function(result){
				alert("Error interno");
			}
		});
	}
	function coloca_info_liga(v_atr_nombre, v_txt_tit, v_txt_info){
		var v_tag_btn = ' <a href="#" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#modal_info" data-txt_tit="'+v_txt_tit+'" data-txt_info="'+v_txt_info+'"><i class="fas fa-lightbulb"></i></a>';
		var v_tag_content = $(v_atr_nombre).html();
		$(v_atr_nombre).html(v_tag_content+v_tag_btn);
	}
	function dep_socio_num(){
		v_socio_num_mujeres = getValueForm('socio_num_mujeres',false);
		v_socio_num_hombres = getValueForm('socio_num_hombres',false);
		vi_socio_num_mujeres = (v_socio_num_mujeres==="")? 0 : parseInt(v_socio_num_mujeres);
		vi_socio_num_hombres = (v_socio_num_hombres==="")? 0 : parseInt(v_socio_num_hombres);
		v_socio_num_total = vi_socio_num_mujeres + vi_socio_num_hombres;
		$("#socio_num_total").val(v_socio_num_total);
	}
	
	function select_opt_select(obj_select){
		//Esta parte solo funciona para el select tipo select2
		v_val = $(obj_select).children("option:selected").val();
		v_id = $(obj_select).attr("id");
		if(v_val!=""){
			$('#select2-'+v_id+'-container').css('color', 'darkcyan');
		}else{
			$('#select2-'+v_id+'-container').css('color', 'gray');
		}
	}
	function grafica_resultados(o_res_indicador){
		if(o_res_indicador.length>0){
			var total_valora = 0;
			var o_data = [];
			for(i in o_res_indicador){
				total_valora = o_res_indicador[i].cat_cuest_modulo.porcentaje;
				o_data[i] = {
						"cat_cuest_modulo_desc":o_res_indicador[i].cat_cuest_modulo.descripcion,
						"total_valora":total_valora
				};
			}
			
			
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			/* Create chart instance */
			var chart = am4core.create("chartdiv", am4charts.RadarChart);

			/* Add data */
			chart.data = o_data;
			/* Create axes */
			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "cat_cuest_modulo_desc";

			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2);
			valueAxis.renderer.axisFills.template.fillOpacity = 0.05;
			valueAxis.max = 100;
			valueAxis.min = 0;

			/* Create and configure series */
			var series = chart.series.push(new am4charts.RadarSeries());
			series.dataFields.valueY = "total_valora";
			series.dataFields.categoryX = "cat_cuest_modulo_desc";
			series.name = "valora";
			series.strokeWidth = 3;
			
			// Enable export
			chart.exporting.menu = new am4core.ExportMenu();
		}
	}
	function grafica_res_cmpte(o_res_indicador){
		if(o_res_indicador.length>0){
			for(i in o_res_indicador){
				var o_res_cuest_modulo = o_res_indicador[i].arr_res_cuest_modulo;
				var v_cat_cuest_modulo_id = o_res_indicador[i].cat_cuest_modulo.cat_cuest_modulo_id;
				grafica_res_cmpte_graf(o_res_cuest_modulo, v_cat_cuest_modulo_id);
				
				
			}
			
		}
	}
	function grafica_res_cmpte_graf(o_res_cuest_modulo, v_cat_cuest_modulo_id){
		var total_valora = 0;
		var o_data = [];
		var j=0;
		var nom_chart = 'chartdiv_ccm'+v_cat_cuest_modulo_id;
		console.log(nom_chart);
		for(i in o_res_cuest_modulo){
			if(i!="tot"){
				total_valora = o_res_cuest_modulo[i].valoracion*100;
				o_data[j++] = {
						"cat_cuest_modulo_desc":o_res_cuest_modulo[i].cmpte_tit,
						"total_valora":total_valora
				};
			}
		}
		if(o_data.length < 3){
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Create chart instance
			var chart = am4core.create(nom_chart, am4charts.XYChart);
			/* Add data */
			chart.data = o_data;
			
			// Create axes
			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "cat_cuest_modulo_desc";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.minGridDistance = 30;

			// Create value axis
			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
			valueAxis.max = 100;
			valueAxis.min = 0;

			// Create series
			var lineSeries = chart.series.push(new am4charts.LineSeries());
			lineSeries.dataFields.valueY = "total_valora";
			lineSeries.dataFields.categoryX = "cat_cuest_modulo_desc";
			lineSeries.name = "valora";
			lineSeries.strokeWidth = 3;
			
			// Add simple bullet
			var circleBullet = lineSeries.bullets.push(new am4charts.CircleBullet());
			circleBullet.circle.stroke = am4core.color("#fff");
			circleBullet.circle.strokeWidth = 2;

			var labelBullet = lineSeries.bullets.push(new am4charts.LabelBullet());
			labelBullet.label.text = "{value}";
			labelBullet.label.dy = -20;
			
		}else{
			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			/* Create chart instance */
			var chart = am4core.create(nom_chart, am4charts.RadarChart);

			/* Add data */
			chart.data = o_data;
			/* Create axes */
			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "cat_cuest_modulo_desc";

			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
			valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2);
			valueAxis.renderer.axisFills.template.fillOpacity = 0.05;
			valueAxis.max = 100;
			valueAxis.min = 0;

			/* Create and configure series */
			var series = chart.series.push(new am4charts.RadarSeries());
			series.dataFields.valueY = "total_valora";
			series.dataFields.categoryX = "cat_cuest_modulo_desc";
			series.name = "valora";
			series.strokeWidth = 3;
			
			// Add simple bullet
			//var circleBullet = series.bullets.push(new am4charts.CircleBullet());
			//circleBullet.circle.stroke = am4core.color("#fff");
			//circleBullet.circle.strokeWidth = 2;

			//var labelBullet = series.bullets.push(new am4charts.LabelBullet());
			//labelBullet.label.text = "{value}";
			//labelBullet.label.dy = -20;
			
		}
		
		
		
		// Enable export
		chart.exporting.menu = new am4core.ExportMenu();
	}
	return{
		activar:function(v_cat_cuest_modulo_id, v_es_lectura){
			//Esta parte solo funciona para el select tipo select2
			$("select").change(function(){
				select_opt_select(this);
			});
			//Esta parte solo funciona para el select tipo select2
			$("select").each(function(index, o_select){
				select_opt_select(o_select);
			});
			$(".btn_guardar_sig").click(function(){
				$("form[name='frm_cuest'] #ccm_siguiente").val(1);
				document.getElementById("frm_cuest").submit();
			});
			if(v_cat_cuest_modulo_id==1){
				if(parseInt(v_es_lectura)==0){
					$("#ubica_estado").change(function(){dep_ubica_estado();});
					$("#persona_tipo").change(function(){
						ocu_div_org();
					});
					$("#socio_num_mujeres, #socio_num_hombres").change(function(){dep_socio_num();});
				}
				ocu_div_org();
			}
			var v_txt_tit = "Información";
			var v_txt_info = "";
			if(v_cat_cuest_modulo_id==3){
				v_txt_info = "Equipo: maniobras de entrada y salidad del producto, conservacion y movimiento de producto al interior de la bodega o silo, monitoreo y determinación de la calidad, monitoreo de las existencias, control de inventarios, control de plagas, aseguramiento de la instalación, contra incendios, control de accesos.";
				v_cmp_nom = (parseInt(v_es_lectura))? 'm2p11r1_desc' : 'm2p11r1';
				coloca_info_liga("label[for='"+v_cmp_nom+"']", v_txt_tit, v_txt_info);
				v_txt_info = "Peso bruto de báscula y de llegada a la instalación, peso tara y peso neto de bascula de salida de la instalación, destino, placas de tractor y remolque, nombre del conductor, fecha y hora de ingreso, descripción del producto, análisis cualitativo del producto, área de firma para la conformidad del conductor de la unidad y analista.";
				v_cmp_nom = (parseInt(v_es_lectura))? 'm2p13r1_desc' : 'm2p13r1';
				coloca_info_liga("label[for='"+v_cmp_nom+"']", v_txt_tit, v_txt_info);
			}
			if(v_cat_cuest_modulo_id==6){
				v_txt_info = "9 elementos: Producto a embarcar, identificación del producto , número de lote o bodega donde se realiza la carga, peso o volumen a cargar, resultados del análisis de calidad al momento de la carga, datos del transportista, datos de la unidad  de transporte, nombre del chofer, destino del producto, número de guía o factura de producto para traslado, datos de quien recibe el producto.";
				v_cmp_nom = (parseInt(v_es_lectura))? 'm5p7_desc' : 'm5p7';
				console.log(v_cmp_nom);
				coloca_info_liga("label[for='"+v_cmp_nom+"']", v_txt_tit, v_txt_info);
			}
		},
		activa_graficas:function(o_res_indicador, v_ver_res_gen, v_ver_res_cmpte){
			if(parseInt(v_ver_res_gen)){
				grafica_resultados(o_res_indicador);
			}else if(parseInt(v_ver_res_cmpte)){
				grafica_res_cmpte(o_res_indicador);
			}
			
		}
		
	}
}();