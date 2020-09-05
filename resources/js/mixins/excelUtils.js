import XLSX from 'xlsx';
export const excelUtils = {
  methods: {
    getExcel(body, name, merges = [], wscols = [], wsrows = []) {
      var wb = XLSX.utils.book_new();
      wb.Props = {
        Title: "SheetJS Tutorial",
        Subject: "Test",
        Author: "Red Stapler",
        CreatedDate: new Date()
      };
      wb.SheetNames.push("Reporte");
      var ws_data = body;
      var ws = XLSX.utils.aoa_to_sheet(ws_data);
      wb.Sheets["Reporte"] = ws;
      if (merges.length) {
        if(!ws['!merges']) ws['!merges'] = [];
        merges.forEach(merge => {
          ws['!merges'].push(merge);
        })
      }

      wscols.forEach((item, index) => {
        wscols[index] = { wch: item };
      });
      ws['!cols'] = wscols;

      wsrows = wsrows.map(e => ({ hpx: e }));
      ws['!rows'] = wsrows;
      
      XLSX.writeFile(wb, `${name}.xlsb`);
    },
    parseHeadNames(file) {
      return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.onload = (e) => {
          var data = e.target.result;
          var workbook = XLSX.read(data, {
            type: 'binary'
          });
          workbook.SheetNames.forEach((sheetName) => {
            // var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName], { blankCell : true });
            let sheetData = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], {
              header: 1,
              defval: '',
              blankrows: true
            });
            resolve(sheetData[0]);
          });
        };
        reader.onerror = function(ex) {
          console.log(ex);
          reject();
        };
        reader.readAsBinaryString(file);
      });
    },
    parseExcel(file) {
      return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.onload = (e) => {
          var data = e.target.result;
          var workbook = XLSX.read(data, {
            type: 'binary'
          });
          workbook.SheetNames.forEach((sheetName) => {
            var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName], { blankCell : true });
            resolve(XL_row_object);
          });
        };
        reader.onerror = function(ex) {
          console.log(ex);
          reject();
        };
        reader.readAsBinaryString(file);
      });
    }
  }
}