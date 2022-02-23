<template>
  <div :id="componentName">
    <template v-if="!error">
      <div v-show='!showEdit'>
        <div 
          v-if="tableButtons.length > 0" 
          class="row mb-2 mr-2 text-right"
        >
          <div class="col">
            <a 
              v-for="button in tableButtons" 
              :key="button" 
              :href="getButtonHref(button, button['itemData'])" 
              :class="buttonClass(button)"
              class="ml-3"
            >
              {{ button['name'] }}
            </a>
          </div>
        </div>
        <table class="table table-hover">
          <thead>
            <tr>
              <th v-for="col in tableColumns" :key="col" scope="col">{{ col }}</th>
              <th v-for="customColumn in customColumns" :key="customColumn" scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for='itemData in data' :key='itemData.id' @click="edit(itemData['id'])" style='cursor:pointer'>
              <template v-for='(item, colName) in itemData' :key='colName'>
                <td v-show="getStructureValue(colName, 'show_in_table')">
                  <template v-if="getStructureValue(colName, 'type') == 'checkbox'">
                    <template v-if="item == '1'" >
                      <i class="fas fa-check"></i>
                    </template>
                    <template v-else>
                      <i style='color:red' class="fas fa-times"></i>
                    </template>
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type') == 'radio'">
                    <div :class="'radio-box radio-box-' + getRadioColor(colName, item)">{{ getStructureValue(colName, 'radio')[item] }}</div>
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type', '') == 'lookup'">
                    <template v-if="parseInt(itemData[getStructureValue(colName, 'lookup_table_col')]) > 0">
                      <a 
                        onclick="window.event.cancelBubble = true"
                        :href="getStructureValue(colName, 'lookup_url', '') + '?' 
                          + getStructureValue(colName, 'lookup_url_type', 'id_form') + 
                          '=' 
                          + itemData[getStructureValue(colName, 'lookup_table_col')]
                        "
                        class="lookup-icon"
                      >
                        <i style='font-size:20px' :class="'fas fa-' + getStructureValue(colName, 'lookup_icon', 'clipboard-list')"></i>
                      </a>
                    </template>
                    <template v-else>
                      <div v-html="getStructureValue(colName, 'lookup_empty_table', '')"/>
                    </template>
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type', 'text') != 'image'">
                    {{ item }} {{ getStructureValue(colName, 'unit') }}
                  </template>
                  <template v-else>
                    <img 
                      :src="'http://localhost/'+ this.dir +'/files/'  + this.fileDir + '/' + checkImage(itemData['image'])" 
                      width="35" 
                      height="35"
                    />
                  </template>
                </td>
              </template>
              <td v-for="customColumn in customColumns" :key="customColumn['except'].length">
                <button
                  v-if="customColumnExcept(customColumn, itemData)"
                  onclick="window.event.cancelBubble = true"
                  @click="customColumnAction(customColumn, itemData)"
                  class="btn btn-warning"
                >
                  {{ customColumn['title'] }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <dia-pagination :params="{
          tableName: this.tableName,
          pages: this.pages,
          conditions: this.conditions
        }"></dia-pagination>
      </div>
      
      <div v-show='showEdit' class='card' style='height:750px;width:100%;'>
        <template v-for='itemData in data'>
          <div v-if="itemData['id'] == showEditId" :key='itemData.id' class="card-header row p-1" style="margin:0px">
            <div class="col">
              <button @click='hideEdit()' class='btn btn-primary'>
                <i class="fas fa-arrow-left color-secondary" aria-hidden="true"></i>
              </button>
            </div>
            <div class="col">
              <a 
                v-for="button in buttons" 
                :key="button" 
                :href="getButtonHref(button, itemData)" 
                :class="buttonClass(button)"
                class="ml-3"
              >
                {{ button['name'] }}
              </a>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-4">
              <div class="row">
                <div class="col">
                  <button @click="save(itemData)" type="button" class="btn mb-2 mb-md-0 btn-success btn-block"><span>Uložiť</span> 
                    <div class="icon d-flex align-items-center justify-content-center">
                      <i class="far fa-save color-success"></i>
                    </div>
                  </button>
                </div>
                <div class="col">
                  <button @click="deleteItem(itemData['id'])" type="button" class="btn mb-2 mb-md-0 btn-danger btn-block"><span>Zmazať</span> 
                    <div class="icon d-flex align-items-center justify-content-center">
                      <i class="fas fa-trash-alt color-red-dark"></i>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </template>
        <div class="card-body">
          <template v-for='itemData in data'>
            <div v-if="itemData['id'] == showEditId" :key='itemData.id'>
              <template v-for='(item, colName) in itemData' :key='colName'>
                <div v-show="getStructureValue(colName, 'show_in_form')" class="form-group row">
                  <label 
                    :for="colName" 
                    v-html="getStructureValue(colName, 'name_in_table', '', true)" 
                    class="col-sm-2 col-form-label"
                  />
                  <div class="col-sm-9">
                    <div class="input-group mb-2">
                      <div v-if="getStructureValue(colName, 'required')" class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-exclamation" :class="classObjectRequired(colName)"></i>
                        </div>
                      </div>
                      <template v-if="getStructureValue(colName, 'type', '') == 'checkbox'">
                        <input 
                          type="checkbox" 
                          class="form-checkbox" 
                          :id="colName" 
                          :name="colName"  
                          true-value="1"
                          false-value="0"
                          v-model="itemData[colName]"
                        />
                      </template>
                      <template v-else-if="getStructureValue(colName, 'type', '') == 'radio'">
                        <div class="mr-3 mt-2" v-for="(radioItem, index) in getStructureValue(colName, 'radio')" :key="radioItem">
                          <div class="radio radio-primary">
                            <input 
                              type="radio" 
                              :id="index" 
                              :name="colName" 
                              :value="index" 
                              v-model="itemData[colName]" 
                              :checked="item == index"
                            />
                            <label :for="index" class="ml-1"><span>{{ radioItem }}</span></label>
                          </div>                   
                        </div>
                      </template>
                      <template v-else-if="getStructureValue(colName, 'type') == 'lookup'">
                        <template v-if="parseInt(itemData[getStructureValue(colName, 'lookup_table_col')]) > 0">
                          <a 
                            onclick="window.event.cancelBubble = true" 
                            :href="getStructureValue(colName, 'lookup_url', '') + '?' 
                              + getStructureValue(colName, 'lookup_url_type', 'id_form') + 
                              '=' 
                              + itemData[getStructureValue(colName, 'lookup_table_col')]
                            "
                             class="btn mb-2 mb-md-0 btn-round btn-outline"
                          >
                          {{ itemData }}
                            {{ getLookupValues(colName, itemData) }} 
                          </a>
                        </template>
                        <template v-else>
                          <template v-if="getStructureValue(colName, 'readonly_in_edit')">
                            <div v-html="getStructureValue(colName, 'lookup_empty_table', '')"/>
                          </template>
                          <template v-else-if="getStructureValue(colName, 'lookup_empty_action')">
                            <a
                              :href="'index.php?action=' + getLookupAction(colName, itemData['id'])"
                              class="btn btn-warning"
                            >
                              {{ getStructureValue(colName, 'lookup_table_empty_text', 'Akcia') }}
                            </a>
                          </template>
                        </template> 
                      </template>
                      <template v-else-if="getStructureValue(colName, 'type') == 'text'">
                        <textarea 
                          class="form-control" 
                          v-model="itemData[colName]"
                          rows="6"
                          :readonly="getStructureValue(colName, 'readonly') "
                        />
                      </template>
                      <template v-else-if="getStructureValue(colName, 'type', '') != 'image'">
                        <input 
                          :type="getStructureValue(colName, 'type', 'text')" 
                          class="form-control" 
                          :class="classObject(colName)" 
                          :id="colName" 
                          v-model="itemData[colName]"
                          :step="getStructureValue(colName, 'step', 0.01)"
                          :readonly="getStructureValue(colName, 'readonly') "
                        />
                        <div v-if="getStructureValue(colName, 'unit')" class="input-group-append">
                          <span class="input-group-text">{{ getStructureValue(colName, 'unit') }}</span>
                        </div>
                      </template>
                      <template v-else>
                        <div :class="imageRequired(colName)">
                          <img 
                            :src="'http://localhost/'+ this.dir +'/files/' + this.fileDir + '/' + checkImage(itemData['image'])" 
                            width="100" 
                            height="100"
                          />
                          <div class='pt-4 pl-3'>
                            <dia-file-uploader :params="{
                              tableName: this.tableName,
                              uploadAction: getStructureValue(colName, 'upload_action'),
                              idItem: itemData['id']
                            }"></dia-file-uploader>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </template>
        </div>
      </div>
    </template>
    <template v-else>
      <div class="text-center" style="margin-top:20%">
        <h1 style='color:grey'>{{ emptyDataMessage }}</h1>
      </div>
    </template>
  </div>
</template>

<script>
  import fileUploader from './FileUploader5.vue';
  import pagination from './Pagination4.vue';

  var diaTableLarge = Object();

  export default {
    components: {
      'dia-file-uploader': fileUploader,
      'dia-pagination': pagination
    },
    props: ['params'],
    data() {
      return Object.assign(diaTableLarge, {
        componentName: "tablelarge",
        showEdit: false,
        showEditId: 0,
        error: false,
        emptyDataMessage: 'No records',
        emptyRequiredInputs: [],
        lookups: [],
        dataToSet: ['pages'],
        customColumns: []
      })
    },
    methods: {   
      getStructureValue(colName, structureParam, defaultReturnParam, addItallic = false) {
        return diaTables.getStructureValue(
          colName, 
          structureParam, 
          defaultReturnParam, 
          this.tableStructure, 
          addItallic
        );
      },
      getRadioColor(colName, item) {
        var color =  this.getStructureValue(colName, 'radio_colors');
        return f.getRadioColor(color, item);
      },
      edit(showEditId) {
        this.showEdit = true;
        this.showEditId = showEditId;
        diaTableLarge.addToUrl('id_form', showEditId);
        diaTableLarge.refactorCustomLinks(this);
        this.recoveryData = this.data;
      },
      hideEdit() {
        this.showEdit = false;
        diaTableLarge.deleteFromUrl('id_form');
        f.loadData(this, "dia_select_with_pagination", this.dataToSet);
      },
      loadData() {
        f.loadData(this, "dia_select_with_pagination", this.dataToSet);
      },
      save(itemData) {
        // Prever prazdne povinne polia
        this.emptyRequiredInputs = diaTableLarge.checkRequiredInputs(itemData, this.tableStructure);

        if (this.emptyRequiredInputs.length < 1) {
          axios.put('index.php?action=dia_vue_update', {
            params: {
              tableName: this.tableName,
              rowId: itemData['id'],
              data: itemData
            }
          }).then(() => {
            this.showEdit = false;
            diaTableLarge.deleteFromUrl('id_form');
            f.loadData(this, "dia_select_with_pagination", this.dataToSet);
            swal({
              title: "Uložené",
              type: "success",
              timer: 600,
              showConfirmButton: false
            })
          })
        }
      },
      deleteItem(rowId) {
        diaTableLarge.itemDelete(
          this.tableName,
          rowId,
          () => {
            diaTableLarge.loadData(this, "dia_select_with_pagination", this.dataToSet);
            this.showEdit = false;
          }
        );
      },
      classObject(item) {
        return diaTables.validateInput(this, item);
      },
      classObjectRequired(item) {
        return {
          'requiredColor': this.emptyRequiredInputs.includes(item)
        }
      },
      buttonClass(button) {
        if (typeof button['class'] != "undefined") {
          return button['class'];
        } else {
          return "btn btn-primary";
        }
      },
      imageRequired(colName) {
        return {
          'row': true,
          'ml-2': this.getStructureValue(colName, 'required')
        }
      },
      getButtonHref(button, itemData) {
        if (typeof button['customLink'] != "undefined") {
          return button['customLink'];
        } else {
          return button['link'] 
            + '?id=' 
            + itemData['id'] 
            + '&previous_page=' 
            + this.currentWebPage 
            + '&previous_page_id_form=' 
            + itemData['id']
          ;
        }
      },
      getLookupValues(colName, col) {
        return diaTableLarge.getLookupValues(colName, col);
      },
      getLookupAction(colName, id) {
        var action = this.getStructureValue(colName, 'lookup_table_empty_action', 'action');

        if (action != "action") {
          action = action.replace("%id%", id);
        }

        return action;
      },
      customColumnAction(customColumn, itemData) {
        var params = {};

        Object.keys(customColumn['params']).forEach((item) => {
          if (item.toString() != "get") {
            params[item] = itemData[customColumn['params'][item]];
          } else {
            params[customColumn['params'][item]] = diaTableLarge.getUrlParam('id');
          }
        })
    
        axios.put('index.php?action=' + customColumn['action'], {
          tableName: customColumn['tableName'],
          data: params
        }).then((res) => {
          //TODO: Refresh by mal fungovat po pridani noveho udaju to pola
          Object.keys(this.customColumns).forEach((index) => {
            this.customColumns[index]['except'].push(parseInt(res.data['id']));
            console.log(this.customColumns[index]['except']);
          })

          location.reload(); 
        });
      },
      customColumnExcept(customColumn, itemData) {
        var returnVal = {};

        if (Object.values(customColumn['except']).includes(parseInt(itemData['id']))) {
          returnVal = false;
        } else {
          returnVal = true;
        }

        return returnVal;
      },
      checkImage(image) {
        if (typeof image == "undefined") {
          return "default.jpg";
        } else {
          return image;
        }
      }
    },
    beforeCreate() {
      diaTableLarge = new Dia();
    },
    beforeMount() {
      f.setComponentParams(this);
      f.initComponent(this, false, "dia_select_with_pagination", this.dataToSet);

      // Custom Component functions
      if (diaTableLarge.getUrlParam('id_form') > 0) {
        this.showEdit = true;
        this.showEditId = diaTableLarge.getUrlParam('id_form');
      }

      this.buttons = this.params['buttons'];
      this.tableButtons = this.params['tableButtons'];
      this.customColumns = this.params['customColumns'];

      this.emptyDataMessage = 
        this.params['emptyDataMessage'] 
        ? this.params['emptyDataMessage'] 
        : 'No records'
      ;
    }
  }
</script>