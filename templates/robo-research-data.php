<?php
/**
 * Created by PhpStorm.
 * Date: 2019-03-26
 * Time: 12:00
 */
?>
<div class="cmb2-wrap form-table repeater">
    <div class="cmb2-metabox repeatable cmb-repeatable-group" data-repeater-list="research-tools">
        <div data-repeater-item>
            <h3 class="cmb-group-title cmbhandle-title"><span>Entry</span></h3>
            <div class="cmb-row table-layout"
                 data-fieldtype="text">
                <div class="cmb-th">
                    <label for="entry_label">Entry
                        Label</label>
                </div>
                <div class="cmb-td">
                    <input type="text" class="regular-text"
                           name="research_name"
                           id="research_name" value="">
                </div>
            </div>

            <div class="cmb-row table-layout"
                 data-fieldtype="text">
                <div class="cmb-th">
                    <label for="entry_data">Entry
                        Data</label>
                </div>
                <div class="cmb-td">
                                            <textarea class="cmb2-textarea-small"
                                                      name="research_data"
                                                      id="research_data" cols="60"
                                                      rows="4"></textarea>
                </div>

            </div>
            <input type="button" data-repeater-delete
                   class="robo-delete-button" value="delete"/>
        </div>
    </div>
    <input data-repeater-create type="button" value="Add"/>
</div>