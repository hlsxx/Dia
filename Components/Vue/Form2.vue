<template>
  <div :id="componentName">
    <form 
      @submit="checkRequiredsInputs"
      action="index.php?action=dia_insert_post" 
      method="POST"
      enctype="multipart/form-data"
    > 
      <div class="card">
        <div class="card-body">
          <div v-for="(colVal, colName) in allTableColumns" :key="colName" class="form-group row">
            <template v-if="getStructureValue(colName, 'hide_in_new_form') !== true">
              <label 
                v-html="getStructureValue(colName, 'name_in_table', colName, true)"
                :for="'form_' + this.tableName + colName"
                class="col-sm-2 col-form-label" 
              />
              <div class="col-sm-9">
                <div class="input-group mb-2">
                  <div v-if="getStructureValue(colName, 'required')" class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-exclamation"></i>
                    </div>
                  </div>
                  <template v-if="getStructureValue(colName, 'type') == 'checkbox'">
                    <input 
                      type="checkbox"
                      class="form-checkbox"
                      :class="validateInput(colName)"
                      :name="colName" 
                      :id="'form_' + this.tableName + colName"
                      :value="formValues[colName]" 
                      :checked="getStructureValue(colName, 'default_value') == 1"
                    />
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type') == 'radio'">
                    <div class="mr-3" v-for="(radioItem, index) in getStructureValue(colName, 'radio', '')" :key="radioItem">
                      <div class="radio radio-primary">
                        <input 
                          type="radio" 
                          :id="'form_' + this.tableName + colName" 
                          :name="colName" 
                          :value="index" 
                          :class="validateInput(colName)"
                          v-model="formValues[colName]" 
                          :checked="getStructureValue(colName, 'default_value') == index"
                        />
                        <label :for="index" class="ml-1"><span>{{ radioItem }}</span></label>
                      </div>  
                    </div>
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type') == 'image'">
                    <input 
                      type="file" 
                      :id="'form_' + this.tableName + colName"
                      :name="colName"
                      :class="validateInput(colName)" 
                      class="pl-2 pt-1"
                    />
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type') == 'text'">
                    <textarea
                      :name="colName"
                      class="form-control" 
                      v-model="formValues[colName]"
                      rows="6"
                    />
                  </template>
                  <template v-else-if="getStructureValue(colName, 'type') == 'lookup'">
                    <select 
                      class="form-select form-select-sm custom-select" 
                      :name="colName"
                      :id="'form_' + this.tableName + colName"
                      v-model="formValues[colName]"
                    >
                      <option disabled value="">Vyberte</option>
                      <option 
                        v-for="col in formLookupsValues[colName]"
                        :key="col.id" 
                        :value="col.id"
                      >{{ getLookupValues(colName, col) }}</option>
                    </select>
                  </template>
                  <template v-else>
                    <input 
                      :placeholder="getStructureValue(colName, 'name_in_table', colName)"
                      :type="getStructureValue(colName, 'type', colName)"
                      :name="colName"  
                      class="form-control"
                      :class="validateInput(colName)" 
                      :id="'form_' + this.tableName + colName"
                      v-model="formValues[colName]"
                    />
                  </template>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div class="card-footer text-center">
          <input type="hidden" name="tableName" :value="tableName"/>
          <input 
            type="submit" 
            class="btn mb-2 mb-md-0 btn-primary btn-block"
            value="Vytvoriť"
          />
        </div>
      </div>
    </form>
  </div>
</template>

<script>
var diaForm = Object();

export default {
  props: ['params'],
  data() {
    return Object.assign(diaForm, {
      uploadAction: "",
      componentName: "form2"
    });
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
    checkRequiredsInputs(e) {
      diaTables.checkRequiredsInputs(e, this);
    },
    validateInput(item) {
      return diaTables.validateInput(this, item);
    },
    getLookupValues(colName, col) {
      return diaForm.getLookupValues(colName, col);
    }
  },
  beforeCreate() {
    diaForm = new Dia();
  },
  mounted() {
    f.setComponentParams(this);
    f.initComponent(this, true);
  }
}
</script>

