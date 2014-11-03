<?php
global $base_url, $user;

$default_firmware_name = $firmware['id'] ? $firmware['name'] : '';
$default_firmware_part = $firmware['id'] ? $firmware['part'] : '';
$default_firmware_version = $firmware['id'] ? $firmware['version'] : '';
$default_firmware_description = $firmware['id'] ? $firmware['description'] : '';
$default_firmware_file = $firmware['id'] ? $firmware['file'] : '';
$file_id = $firmware['id'] ? $firmware['file_id'] : '';
$action = $firmware['id'] ? '/firmware/update' : '/firmware/save';
$submit_name = $firmware['id'] ? t('Update Firmware') : t('Add New Firmware');
?>
<form id="form_firmware" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo $base_url . $action; ?>">
  <div>
    <table class="form-item-table-full add_new">
      <tbody>
        <tr>
          <td style="padding-left: 0px;">
            <h4>1. Select Device Type</h4>
          </td>
        </tr>
        <tr>
          <td>
            <div class="form-item-div">
              <div class="form-item-left">
                <span title="This field is required." class="form-required">*</span>
              </div>
              <div class="form-item" id="edit-field-device-type-nid-nid-wrapper">
                <?php echo $select_device; ?>	
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td style="padding-left: 0px;">
            <h4>2. Enter Information about the new Firmware</h4>
          </td>
        </tr>
        <tr>
          <td>
            <div class="label_left">
              <label>Firmware Name:</label>
            </div>
            <div class="form-item-div">
              <div class="form-item-left">
                <span class="form-required" title="This field is required.">*</span>
              </div>
              <div>
                <div class="form-item" id="edit-title-wrapper">
                  <input type="text" maxlength="255" name="firmware_name" id="firmware_name" size="60" value="<?php echo $default_firmware_name ?>" class="form-text required" placeholder="'Enter Firmware Name'"/>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="label_left">
              <label title="Enter '0' if there is no Part #">Firmware Part #:</label>
            </div>
            <div class="form-item-div">
              <div class="form-item-left">
                <span class="form-required" title="This field is required.">*</span>
              </div>
              <div>
                <div class="form-item">
                  <input type="text" maxlength="255" name="firmware_part" id="firmware_part" size="60" value="<?php echo $default_firmware_part ?>" class="form-text required" placeholder="Enter Firmware Part #"/>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="label_left">
              <label>Firmware Version:</label>
            </div>
            <div class="form-item-div">
              <div class="form-item-left">
                <span class="form-required" title="This field is required.">*</span>
              </div>
              <div>
                <div class="form-item">
                  <input type="text" maxlength="255" name="firmware_version" id="firmware_version" size="60" value="<?php echo $default_firmware_version ?>"
                         class="form-text required" placeholder="Enter Firmware Version"/>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="form-item-div without_label_left">
              <div class="form-item-left">
                <label>Firmware Description:</label>
                <div>
                  <div class="form-item">
                    <input type="text" maxlength="255" name="firmware_description" id="firmware_description" size="60" value="<?php echo $default_firmware_description ?>"
                           class="form-text required" placeholder="Enter Firmware Description"/>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="label_left">
              <label title="Enter '0' if there is no Part #">Firmware Status:</label>
            </div>
            <div class="form-item-div">
              <div class="form-item-left">
                <span title="This field is required." class="form-required">*</span>
              </div>
              <div>
                <div class="form-item" id="edit-field-device-firmware-version-0-value-wrapper">
                  <?php echo $select_firmware_status; ?>
                </div>
              </div>
            </div>
          </td>
        </tr>     
        <tr>
          <td>
            <div class="form-item-div">
              <div class="label_left">
                <label>File Name: (Browse to select a file)</label>
              </div>
              <div class="form-item-left">
                <span class="form-required" title="This field is required.">*</span>
              </div>
              <div style="width: 90%" class="form-item-left">
                <div class="clear_div">
                  <div id="edit-field-sw-file-0-ahah-wrapper">
                    <div id="edit-field-sw-file-0-upload-wrapper" class="form-item">
                      <div class="filefield-element clear-block">
                        <div class="widget-edit">
                          <div id="edit-field-sw-file-0-upload-wrapper" class="form-item">
                            <div class="filefield-upload clear-block">
                              <span id="span_file_name" style="display: none"><?php echo $default_firmware_file; ?></span>
                              <input type="text" readonly="readonly" id="firmware_fileName" name="firmware_fileName" style="width: 125px;" value="<?php echo $default_firmware_file; ?>">
                              <input type="button" style="height: auto;" value="Browse" id="btn_browse"> 
                              <input type="file" size="22" id="firmware_file" class="form-file" name="firmware_file" onchange="setFileValue(this.value)" value="<?php echo $default_firmware_file; ?>"> 
                              <input type="button" id="btn_upload" class="form-submit" value="Upload" onclick="uploadFile(event)"> 
                              <input type="button" id="btn_remove" class="form-submit" value="Remove" style="display: none" onclick="removeFile(event)">
                              <input type="hidden" id="file_id" name="file_id" value="<?php echo $file_id; ?>">
                            </div>
                            <div class="description">
                              Maximum file size: <em>110 MB</em><br>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="clear: both;"></div>
          </td>
        </tr>
        <tr>
          <td style="padding-left: 0px;">
            <h4>3. Select the Hardware configuration(s) from the table below that are compatible with the new firmware</h4>
          </td>
        </tr>
        <tr>
          <td>
            <span title="This field is required." class="form-required">*</span>
            <?php echo t('Use checkbox to select Hardware configuration(s) to associate to the new Firmware'); ?>
            <div id="hc_list_wraper" style="overflow: auto; max-height: 400px; float: left; position: relative; width: 100%">
			</div>
          </td>
        </tr>
        
        		
						
        <tr>
          <td style="padding-left: 0px;">
            <h4>4. Select Regulatory Exception</h4>
          </td>
        </tr>
        <tr>
          <td>
            <div style="float:left">
              <?php echo $reg_excep_list; ?>
            </div>
            <div style="float:left; padding-left:20px">
              <input type="button" value="Add" class="form-submit" onclick="addRegulatoryExp()" />
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <table id="regulatory_exp_list" style="width: 40%">
              <tr>
                <th style="width: 70%">Country</th>
                <th style="width: 30%"></th>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><br/></td>
        </tr>
        <tr>
          <td>
            <table width="100%" style="border: none">
              <tbody style="border: none">
                <tr>
                  <?php  if($firmware['id']) {?>
                  
                  <td width="80%" align="right"><a href="<?php echo $base_url; ?>/firmware/list" id="secondary_submit">Cancel</a></td>
                  <td width="10%" align="right"><a href="<?php echo $base_url; ?>/firmware/delete?id=<?php echo $firmware['id'] ?>" id="secondary_submit">Delete</a></td>
                  <td width="10%"><input id="btn_submit" type="button" class="non_active_blue" value="<?php echo $submit_name; ?>" onclick='save_firmware()' disabled="disabled"></td>
                
                  <?php }  else { ?>
                
                  <td width="75%" align="right"><a href="<?php echo $base_url; ?>/firmware/list" id="secondary_submit">Cancel</a></td>
                  <td width="25%"><input id="btn_submit" type="button" class="non_active_blue" value="<?php echo $submit_name; ?>" onclick='save_firmware()' disabled="disabled"></td>
                
                  <?php } ?>
                  
                  </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <input type="hidden" name="device_type_name"></input>
  <input type="hidden" name="old_file_path" value="<?php echo $old_file_path ?>"></input> 
  <input type="hidden" name="device_type_list" id="device_type_list"></input>
  <input type="hidden" name="device_type_id_list" id="device_type_id_list"></input>
  <input type="hidden" value="asc" id="edit-hw-list-sort" name="hw_list_sort">
  <input type="hidden" value="title" id="edit-hw-list-order" name="hw_list_order">
  <input type="hidden" value="0" id="edit-hw-list-page" name="hw_list_page">
  <input type="hidden" id="firmware_id" name="firmware_id" value="<?php echo $firmware['id'] ?>" />
  <input type="hidden" id="selectedCountryStr" name="selectedCountryStr" value="<?php echo $selectedCountryStr ?>" />
</form>

