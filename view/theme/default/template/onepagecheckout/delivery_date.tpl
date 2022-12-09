<div class="extsm-12">
	<div class="full-payment-method format_load">
		<div class="content-delivery-method">
			<div class="extpanel extpanel-default">
				<div class="extpanel-heading">
				   <h4 class="extpanel-title"><i class="fa fa-clock-o"></i> <?php echo $heading_title; ?></h4>
				</div>
				<div class="extpanel-body delivery-Date-content ext-delivery-date">
					<div class="form-group <?php echo ($delivery_required ? 'required' : ''); ?>">
						<label class="col-sm-3 control-label"><?php echo $label; ?></label>
						<div class="col-sm-7">
							<div class="input-group date date-error">
								<input type="text" name="delivery_date" value="<?php echo $delivery_date; ?>" data-date-format="YYYY-MM-DD"  class="form-control date" />
								<span class="input-group-btn">
									<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
var disablesweeks = [];
  <?php foreach($delivery_weekend as $week){ ?>
  disablesweeks.push(<?php echo $week; ?>);
  <?php } ?>
  
$('.date').datetimepicker({
	pickTime: false,
	minDate: moment().add('<?php echo $start; ?>', 'days'),
    maxDate: moment().add('<?php echo $end; ?>', 'days'),
	<?php if($disable_days){ ?>
	disabledDates: <?php echo $disable_days; ?>,
	<?php } ?>
	daysOfWeekDisabled: disablesweeks,
	useCurrent: false,
	autoclose: 1,
	defaultDate: '<?php echo $next_date; ?>',
});
</script>