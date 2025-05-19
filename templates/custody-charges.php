<h2><?php _e( 'Custody charges', 'cplat' ); ?></h2>

<div class="platform-data-table cplat-repeat">
	<table class="platform-data">
		<thead>
			<th width="20%" ><?php _e( 'Name', 'cplat' ); ?></th>
			<th width="12%" ><?php _e( 'Type', 'cplat' ); ?></th>
			<th width="3%" ><?php _e( 'Tiered', 'cplat' ); ?></th>
			<th width="8%" ><?php _e( 'AUA from', 'cplat' ); ?></th>
			<th width="8%" ><?php _e( 'AUA to', 'cplat' ); ?></th>
			<th width="6%" ><?php _e( 'GIA', 'cplat' ); ?></th>
			<th width="6%" ><?php _e( 'ISA', 'cplat' ); ?></th>
			<th width="6%" ><?php _e( 'JISA', 'cplat' ); ?></th>
			<th width="6%" ><?php _e( 'Sipp', 'cplat' ); ?></th>
            <th width="6%"><?php _e( 'JSipp', 'cplat' ); ?></th>
            <th width="6%" ><?php _e( 'LISA', 'cplat' ); ?></th>
            <th width="3%" ><?php _e( 'VAT', 'cplat' ); ?></th>
			<th width="4%" ><?php _e( 'Remove', 'cplat' ); ?></th>
		</thead>
		<tbody class="container">
			<?php
			// $platform_product_annual_charges_labels = array(
			// 	__( 'General investment account', 'cplat' ),
			// 	__( 'ISA', 'cplat' ),
			// 	__( 'Junior ISA', 'cplat' ),
			// 	__( 'Sipp', 'cplat' ),
			// 	__( 'Child Sipp', 'cplat' )
			// );
			$count = 0;
			if (isset($all_data['charges']) && is_array($all_data['charges'])) :
				$custody_charges = array_filter( $all_data['charges'], function ( $v ) {
					return $v['fee_type_id'] == 1;
				} );
			foreach( $custody_charges as $data ) :
				$type = isset($data['calc_type_id']) ? $data['calc_type_id'] : '';
			?>
			<tr class="data-row">
                <th><input type="text" name="custody_charges[<?php echo $count ?>][fee_name]"
                           value="<?php echo cplat_text( $data['name'], '' ); ?>"></th>

                <td>
					<select name="custody_charges[<?php echo $count; ?>][calc_type_id]">
					<?php foreach ( $charge_types as $key => $label ) : ?>
					<option <?php echo selected($type, $key ); ?> value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
					<?php endforeach; ?>
					</select>
				</td>

				<td><input type="checkbox" <?php echo checked( $data['tiered'], '1' ) ?> name="custody_charges[<?php echo $count; ?>][tiered]" value="1"></td>
				<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="custody_charges[<?php echo $count; ?>][aua_from]" value="<?php echo cplat_text($data['aua_from'], ''); ?>" class="numeric-input"></td>
				<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="custody_charges[<?php echo $count; ?>][aua_to]" value="<?php echo cplat_text($data['aua_to'], ''); ?>" class="numeric-input"></td>

				<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span>
                    <input type="text" name="custody_charges[<?php echo $count; ?>][gia]" value="<?php echo cplat_text($data['gia'], ''); ?>" class="numeric-input"></td>
				<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span>
                    <input type="text" name="custody_charges[<?php echo $count; ?>][isa]" value="<?php echo cplat_text($data['isa'], ''); ?>" class="numeric-input"></td>
				<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span>
                    <input type="text" name="custody_charges[<?php echo $count; ?>][jisa]" value="<?php echo cplat_text($data['jisa'], ''); ?>" class="numeric-input"></td>
				<td class="number-value"><span class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty($type) ? '%' : $currency ?></span>
                    <input type="text" name="custody_charges[<?php echo $count; ?>][sipp]" value="<?php echo cplat_text($data['sipp'], ''); ?>" class="numeric-input"></td>
                <td class="number-value"><span
                            class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty( $type ) ? '%' : $currency ?></span>
                    <input type="text" name="custody_charges[<?php echo $count; ?>][jsipp]"
                           value="<?php echo cplat_text( $data['jsipp'], '' ); ?>" class="numeric-input"></td>
                <td class="number-value"><span
                            class="symbol"><?php echo $type === Calculator_Compare::CALC_TYPE_AD_VALORAM || empty( $type ) ? '%' : $currency ?></span><input
                            type="text" name="custody_charges[<?php echo $count; ?>][lisa]" class="numeric-input"
                            value="<?php echo cplat_text( $data['lisa'], '' ); ?>"></td>
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
			<tr class="template data-row">
				<th><input type="text" name="custody_charges[{{row-count-placeholder}}][fee_name]" value=""></th>
                <td>
					<select name="custody_charges[{{row-count-placeholder}}][calc_type_id]">
					<?php foreach ( $charge_types as $key => $label ) : ?>
					<option value="<?php echo esc_attr($key); ?>"><?php echo esc_attr($label); ?></option>
					<?php endforeach; ?>
					</select>
				</td>
				<td><input type="checkbox" name="custody_charges[{{row-count-placeholder}}][tiered]" value="1"></td>
				<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="custody_charges[{{row-count-placeholder}}][aua_from]" value="" class="numeric-input"></td>
				<td class="currency-value"><span class="symbol"><?php echo $currency; ?></span><input type="text" name="custody_charges[{{row-count-placeholder}}][aua_to]" value="" class="numeric-input"></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="custody_charges[{{row-count-placeholder}}][gia]" value="" class="numeric-input"></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="custody_charges[{{row-count-placeholder}}][isa]" value="" class="numeric-input"></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="custody_charges[{{row-count-placeholder}}][jisa]" value="" class="numeric-input"></td>
				<td class="number-value"><span class="symbol">%</span><input type="text" name="custody_charges[{{row-count-placeholder}}][sipp]" value="" class="numeric-input"></td>
                <td class="number-value"><span class="symbol">%</span><input type="text"
                                                                             name="custody_charges[{{row-count-placeholder}}][jsipp]"
                                                                             value="" class="numeric-input"></td>
                <td class="number-value"><span class="symbol">%</span><input type="text"
                                                                             name="custody_charges[{{row-count-placeholder}}][lisa]"
                                                                             value="" class="numeric-input"></td>
                <td><input type="checkbox" name="custody_charges[{{row-count-placeholder}}][vat]" value="1">
                    <input type="hidden" value="1" name="custody_charges[{{row-count-placeholder}}][fee_type_id]" id="custody_charges[{{row-count-placeholder}}][fee-type-id]"/>
                </td>
				<td><span class="remove"><?php _e('Remove', 'cplat'); ?></span></td>
			</tr>
		</tbody>
		<tfoot><tr><td colspan="12"><span class="add repeatable-btn"><?php _e('ADD ROW', 'cplat'); ?></span></td></tr></tfoot>
	</table>
</div>
