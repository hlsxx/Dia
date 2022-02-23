<template>
  <div>
    <table class="table table-hover" :class="[css]" :style="[style]">
      <thead>
        <tr>
          <th v-for='(item, index) in data[0]' :key="index">
            {{ formatter[index] }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for='(items, indexs) in data' :key="indexs">
          <td v-for="(item, index) in items" :style="[columnStyle[index] ? columnStyle[index] : '']" :key="index"> 
            <template v-if="checkType(item)">
              {{ item }}
            </template>
            <template v-else v-for="(button) in items[index]" :key="button">
              <button v-if="button == 'delete'" @click="itemDelete(items['id'])" class='btn btn-danger mr-2'>🗑</button>
              <button type="button" v-else @click="itemEdit(items['id'])" class='btn btn-warning mr-2' data-toggle="modal" :data-target="tableName_id">✎</button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>
        <li 
          v-for="page in pages" 
          :key="page" 
          class="page-item"
        >
          <button 
            @click="loadPage(page)"
            class="page-link"
          >{{ page }}</button>
        </li>
        <li class="page-item">
          <a class="page-link" href="#">Next</a>
        </li>
      </ul>
    </nav>
    <div v-show="showEdit" :id="tableName_id" class="modal" style="display:block">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Table: <b>{{ tableName }}</b></h5>
            <button @click="showEdit=false" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group" v-for='(item, index) in dataEdit' :key="index">
              <input v-if="index != 'id'" type="text" class="form-control" v-model='dataEdit[index]' required>
              <input v-else type="text" class="form-control" :value="item" disabled>
            </div>
          </div>
          <div class="modal-footer" style="display:block">
            <button @click.prevent="editFormSave()" type="submit" class="btn btn-success">Uložiť</button>
            <button class="btn btn-danger" @click="showEdit = false">Zavrieť</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        data: [],
        columns: [],
        buttons: [],
        tableName: '',
        href_tableName: '',
        showEdit: false,
        dataEdit: [],
        formatter: [],
        css: '',
        style: '',
        //formInputs: [],
        columnStyle: [],
        rowStyle: [],
        tableName_id: '',
        pages: 0
      }
    },
    props: ['table_params'],
    methods: {
      // NACITANIE DAT ZO ZADANEJ TABULKY
      loadData() {
        axios.post(
          'index.php?action=dia_vue_table', 
          { params: this.table_params }
        ).then((res) => {
          this.data = res.data.table_data;
          this.formatter = res.data.formatter;
          this.pages = res.data.pages;
        })
      },
      loadPage(page) {
        this.table_params['currentPage'] = page;
        this.loadData();
      },
      showEditFormFunc(params) {
        if (params.success) {
          this.showEdit = 'block';
          this.dataEdit = params.id;
          axios.post('index.php?action=dia_vue_select', {
            params: {
              tableName: this.table_params['tableName'],
              table_id: params.id,
              form_inputs: this.table_params['formInputs'],
            }
          }).then((res) => {
            this.dataEdit = res.data[0];
          })
        }
        else {
          this.showEdit = 'hidden';
        }
      },
      itemDelete(table_id) {
        var table = this;
        swal({
          title: "Ste si istý?",
          text: "Naozaj chcete vymazať tento záznam?",
          type: "warning",
          showCancelButton: true,
          cancelButtonClass: "btn btn-secondary",
          confirmButtonClass: "btn btn-danger",
          confirmButtonText: "Áno",
          cancelButtonText: "Nie",
          closeOnConfirm: false,
          closeOnCancel: false,
        },
        function(isConfirm) {
          if (isConfirm) {
            axios.post('index.php?action=dia_delete', {
              tableName: table.tableName,
              id: table_id
            })
            swal({
              title: "Vymazané",
              text: "Záznam bol vymazaný!",
              type: "success"
            },
            table.loadData()
            )
          } else {
            swal("Zrušené", "Záznam nebol vymazaný!", "warning") 
          }
        });
      },
      itemEdit(table_id) {
        this.showEditFormFunc({ success: true, id: table_id });
      },
      editFormSave() {
        axios.put('index.php?action=dia_vue_update', {
          params: {
            tableName: this.tableName,
            data: this.dataEdit
          }
        });
        //this.showEdit = false;
        this.loadData();
      },
      checkType(item) {
        return typeof item != 'object';
      }
    },
    mounted() {
      // Ak su data dosadene cez PHP tak nacitaj
      this.conditions = this.table_params['conditions'];

      if (this.table_params['data'].length > 0) {
        console.log(this.table_params['data'].length);
      } else {
        this.loadData();
        this.tableName = this.table_params['tableName'];
      }
    },
  };
</script>