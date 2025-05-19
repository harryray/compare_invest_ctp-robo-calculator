<h2><?php _e( 'Annual Product Fee', 'cplat' ); ?></h2>

<div class="platform-data-table cplat-repeat">
	<table class="platform-data">
		<thead>
			<th width="40%" ><?php _e( 'Name', 'cplat' ); ?></th>
			<th width="40%" ><?php _e( 'Fee', 'cplat' ); ?></th>
		</thead>
		<tbody class="container">
			<tr class="data-row">
				<th>
					<input type="text" disabled name="annual_product_fee_label" value="Annual Product Fee">
				</th>
				<td class="number-value annual-product-fee">
					<span class="symbol">£</span>
					<input type="text" name="annual_product_fee" class="numeric-input" value="<?php echo cplat_text( $data['annual_product_fee'], '' ); ?>" style="width: 100%!important;">
				</td>
				</td>
			</tr>
		</tbody>
		<tfoot></tfoot>
	</table>
</div>
