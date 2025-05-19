<h2><?php _e( 'Annual Product Fee', 'cplat' ); ?></h2>

<div class="platform-data-table cplat-repeat">
	<table class="platform-data">
		<thead>
			<th width="40%" ><?php _e( 'Name', 'cplat' ); ?></th>
			<th width="40%" ><?php _e( 'Fee', 'cplat' ); ?></th>
			<th width="10%" ><?php _e( 'VAT', 'cplat' ); ?></th>
			<th width="10%" ><?php _e( 'Remove', 'cplat' ); ?></th>
		</thead>
		<tbody class="container">
			<?php
			$count = 0;
			if (isset($all_data['charges']) && is_array($all_data['charges'])) :
				$custody_charges = array_filter( $all_data['charges'], function ( $v ) {
					return $v['fee_type_id'] == 1;
				} );
			foreach( $custody_charges as $data ) :
				$type = isset($data['calc_type_id']) ? $data['calc_type_id'] : '';
			?>
			<tr class="data-row">
				<th>
					<input type="text" name="custody_charges[<?php echo $count ?>][fee_name]" value="<?php echo cplat_text( $data['name'], '' ); ?>">
				</th>
				<td class="number-value annual-product-fee">
					<span class="symbol">£</span>
					<input type="text" name="custody_charges[<?php echo $count; ?>][annual_product_fee]" class="numeric-input" value="<?php echo cplat_text( $data['annual_product_fee'], '' ); ?>" style="width: 100%!important;">
				</td>
				<td><input type="checkbox" <?php echo checked( $data['vat'], '1' ) ?> name="custody_charges[<?php echo $count; ?>][vat]" value="1">
				<?php if(isset($data['id'])){?>
						<input type="hidden" value="<?php echo $data['id']?>" name="custody_charges[<?php echo $count; ?>][fee_id]" id="custody_charges[<?php echo $count; ?>][fee-id]" class="fee-id"/><?php }?>
						<input type="hidden" value="1" name="custody_charges[<?php echo $count; ?>][fee_type_id]" id="custody_charges[<?php echo $count; ?>][fee-type-id]"/>
				</td>

				<td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
			</tr>
			<?php $count++; endforeach;?>
            <input type="hidden" name="custody_charges[deleted]" value="" class="deleted"/>
            <?php endif; ?>
		</tbody>
		<tfoot></tfoot>
	</table>
</div>
