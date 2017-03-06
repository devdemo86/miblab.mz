<!-- 

	Graphics page

-->

@extends('app')

@section('content')

<div class="row">

	<div class="col-md-12 col-header">
		<h2>Графическая аналитика</h2>
		<hr>
	</div>

	<div class="col-md-10 col-md-pull-1 col-md-push-1 col-body">

		<div id="js--hc-allPeriod"></div>
		<hr>
		<div id="js--hc-curMonth"></div>
		<hr>
		<div id="js--hc-sumAllKey"></div>
		<hr>
		<div id="js--hc-sum30DaysSO"></div>
		<hr>
		<div id="js--hc-sumMonthPoint"></div>
		<hr>
		<div id="js--hc-sumWeek"></div>
		<hr>

	</div>

	<div class="col-md-12 col-footer">
		<hr>
	</div>

</div>

@stop

@section('script')
<script>
	var sumAll = [], sumMonth = [], sumAllKey = [], sumAllKeyPoint = [], sum30DaysSO = []; sumMonthPoint = [], sumWeek = [];
</script>

@foreach($sumAll as $row)
<script>
	sumAll.push({ y : {{ $row['sum'] = empty($row['sum']) ? 0 : $row['sum'] }}, name : "{{ $row['costs'] }}" });
</script>
@endforeach

@foreach($sumMonth as $row)
<script>
	sumMonth.push({ y : {{ $row['sum'] = empty($row['sum']) ? 0 : $row['sum'] }}, name : "{{ $row['costs'] }}" });
</script>
@endforeach

<script>
	sumAllKey.push(graphParseData(['Прибыль', {{ $sumAllKey['profit'] }}]));
	sumAllKey.push(graphParseData(['Продажи', {{ $sumAllKey['orders'] }}]));
	sumAllKey.push(graphParseData(['Расходы', {{ $sumAllKey['costs'] }}]));
	sumAllKey.push(graphParseData(['Закупка', {{ $sumAllKey['supply'] }}]));
	sumAllKey.push(graphParseData(['Склад', {{ $sumAllKey['stock'] }}]));
</script>

@foreach($sumAllKeyPoint as $key => $row)
<script>
	$('.col-body').append('<div class="hc-line" id="js--hc-sumAllKeyPoint_{{ $key }}"></div>');

	sumAllKeyPoint.push(graphParseData(['Прибыль', {{ $row['profit'] }}]));
	sumAllKeyPoint.push(graphParseData(['Продажи', {{ $row['orders'] }}]));
	sumAllKeyPoint.push(graphParseData(['Расходы', {{ $row['costs'] }}]));
	sumAllKeyPoint.push(graphParseData(['Закупка', {{ $row['supply'] }}]));
	sumAllKeyPoint.push(graphParseData(['Склад', {{ $row['stock'] }}]));

	hcPie(JSON.stringify(sumAllKeyPoint), 'js--hc-sumAllKeyPoint_{{ $key }}', 'Ключевые показатели по точке #{{ $key }}');
	sumAllKeyPoint = [];
</script>
@endforeach

@foreach($sumMonthPoint as $row)
<script>
	sumMonthPoint.push(graphParseData(['Точка #{{ $row['point'] }}', {{ $row['sum'] }}]));
	sumMonthPoint = [];
</script>
@endforeach

@foreach($sumWeek as $row)
<script>
	sumWeek.push(graphParseData(['{{ $row['date'] }}', {{ $row['sum'] }}]));
</script>
@endforeach

<script>
	sum30DaysSO.push(['Закупка за 30 дней', {{ $sum30DaysSupply = empty($sum30DaysSupply) ? 0 : $sum30DaysSupply }}]);
	sum30DaysSO.push(['Реализация за 30 дней', {{ $sum30DaysOrders = empty($sum30DaysOrders) ? 0 : $sum30DaysOrders }}]);

	hcColumn(JSON.stringify(sumAll), 'js--hc-allPeriod', 'За весь период');
	hcColumn(JSON.stringify(sumMonth), 'js--hc-curMonth', 'За период');
	hcPie(JSON.stringify(sumAllKey), 'js--hc-sumAllKey', 'Ключевые показатели');
	hcWdl(JSON.stringify(sum30DaysSO), 'js--hc-sum30DaysSO', 'Закупка и реализация');
	hcPie(JSON.stringify(sumMonthPoint), 'js--hc-sumMonthPoint', 'Прибыль понедельно по точкам (за 30 дней)');
	hcInverted(JSON.stringify(sumWeek), 'js--hc-sumWeek', 'Прибыль понедельно (за 30 дней)')
</script>
@stop

<style>
.hc-line {
	display: inline-block;
	width: 470;
}
</style>