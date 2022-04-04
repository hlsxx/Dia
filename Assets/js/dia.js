class Dia extends Config {

  constructor() {
    super();
    this.currentWebPage = this.getCurrentWebPage();
    this.fileDir = "";

    this.tableName = "";
    this.structureName = "";
    this.data = [];
    this.conditions = [];
    this.tableStructure = [];
    this.error = "";
    this.componentUid = ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );

    this.tableColumns = {};
    this.tableColumnsKeys = [];

    this.formColumns = [];
    this.allTableColumns = [];
    this.lookupsValues = {};

    // Array for new form of table
    this.formValues = [];
    this.formLookupsValues = [];
    this.emptyRequiredInputs = [];
  }

  getLastWord(stringToCut, delimeter) {
    var n = stringToCut.split(delimeter);
    return n[n.length - 1];
  };

  getCurrentWebPage() {
    return this.getLastWord(location.pathname, "/");
  }

  checkRequiredInputs(itemsData, tableStructure) {
    var errorInputs = [];
    Object.keys(tableStructure).forEach((item) => {
      if (tableStructure[item]['required']) {
        if (itemsData[item] == '') {
          //alert('TODO: PRAZDNE POVINNE POLIA!');
          errorInputs.push(item);
        }
      }
    })

    return errorInputs;
  }

  itemDelete(tableName, rowId, callback) {
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
          tableName: tableName,
          id: rowId
        })
        swal({
          title: "Vymazané",
          text: "Záznam bol vymazaný!",
          type: "success"
        })
        if (typeof callback == "function") {
          callback();
        }
      } else {
        swal("Zrušené", "Záznam nebol vymazaný!", "warning") 
      }
    });
  }

  // Nacitavaj data asynchronne pri komponentach v komponentach
  // musi data nacitat skor nez ich vklada do child komponenty
  async loadData(_this, customAction = "", dataToSet = []) {
    this.emptyRequiredInputs = [];
    customAction = customAction != "" ? customAction : "dia_select";

    await axios.post('index.php?action=' + customAction, {
      params: {
        tableName: _this.tableName,
        conditions: _this.conditions
      }
    }).then((res) => {
      if (res.data.status != 'fail') {
        _this.data = res.data['data'];
        dataToSet.forEach((item) => {
          _this[item] = res.data[item];
        })
      } else {
        _this.error = true;
        console.log(res);
      }
    })
  }

  setComponentData(_this, customAction = "", dataToSet = []) {
    if (_this.params['data'].length > 0) {
      _this.data = _this.params['data'];
    } else {
      this.loadData(_this, customAction, dataToSet);
    }
  }

  getLookupValues(colName, col) {
    var lookupColumns = this.tableStructure[colName]['lookup_columns'];
    var returnValue = "";

    if (typeof lookupColumns != "undefined") {
      Object.keys(lookupColumns).forEach((key) => {
        returnValue += col[lookupColumns[key]] + " ";
      })
    } else {
      returnValue = col;
    }

    return returnValue;
  }

  onChangeAction(colName, itemData) {
    var returnVal;

    var onChange = this.tableStructure[colName]['onchange'];

    if (onChange) {
      var split = onChange.split("|");

      split.forEach((item, index) => {
        if (item.includes("{%")) { // Inputs whitch will make something
          if (index > 1) {

            // If input includes something else e.g.: 1.{%variable%} cut this
            var startInput = split[index].indexOf("{%");
            if (startInput > 0) {
              var inputName = split[index].slice(startInput);
            } else {
              var inputName = split[index];
            }

            inputName = inputName.replace("{%", "");
            inputName = inputName.replace("%}", "");
            split[index] = split[index].replace("{%" + inputName + "%}", itemData[inputName]);
          } else { // This is input which will changed
            split[index] = split[index].replace("{%", "");
            split[index] = split[index].replace("%}", "");
          }
        }
      })

      if (split[3] == '/') {
        returnVal = parseFloat((split[2] / split[4])).toFixed(2);
      }

      itemData[split[0]] = returnVal;
    }
  }

}