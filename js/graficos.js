/* 
 * Esse deve ser incluído depois do mdb para exibir a mensagem de nenhum dado disponível
 */
Chart.plugins.register({
    afterDraw: function (chart) {
        if (chart.data.datasets.length === 0 ||
                (chart.data.datasets.length === 1 &&
                        chart.data.datasets[0].data.filter((e) => e != '0').length === 0)
                ) {
            // No data is present
            var ctx = chart.chart.ctx;
            var width = chart.chart.width;
            var height = chart.chart.height;
            chart.clear();

            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = "16px normal 'Helvetica Nueue'";
            ctx.fillText('Nenhum dado disponível', width / 2, height / 2);
            ctx.restore();
        }
    }
});
/*
 Chart.plugins.register({
 beforeRender: function (chart) {
 if (chart.config.options.showAllTooltips) {
 // create an array of tooltips
 // we can't use the chart tooltip because there is only one tooltip per chart
 chart.pluginTooltips = [];
 chart.config.data.datasets.forEach(function (dataset, i) {
 chart.getDatasetMeta(i).data.forEach(function (sector, j) {
 chart.pluginTooltips.push(new Chart.Tooltip({
 _chart: chart.chart,
 _chartInstance: chart,
 _data: chart.data,
 _options: chart.options,
 _active: [sector]
 }, chart));
 });
 });
 
 // turn off normal tooltips
 chart.options.tooltips.enabled = false;
 }
 },
 afterDraw: function (chart, easing) {
 console.log(chart)
 if (chart.config.options.showAllTooltips) {
 // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
 if (!chart.allTooltipsOnce) {
 if (easing !== 1)
 return;
 chart.allTooltipsOnce = true;
 }
 
 // turn on tooltips
 chart.options.tooltips.enabled = true;
 Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
 tooltip.initialize();
 tooltip.update();
 // we don't actually need this since we are not animating tooltips
 tooltip.pivot();
 tooltip.transition(easing).draw();
 });
 chart.options.tooltips.enabled = false;
 }
 }
 });
 
 
 
 };*/
