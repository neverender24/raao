<template>
    <div class="modal fade" id="modalRaaodsLedger" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Ledger</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <datatable
                                :columns="columns"
                                :sortKey="sortKey"
                                :sortOrders="sortOrders"
                                @sort="sortBy"
                            >
                                <tbody role="rowgroup">
                                    <tr role="row" v-for="(item, index) in data" :key="item.recid">
                                        <td>{{ item.fdate }}</td>
                                        <td>{{ item.fparticulars }}</td>
                                        <td>{{ item.payee ? item.payee.PAYEE:'' }}</td>
                                        <td>{{ item.frefno }}</td>
                                        <td style="text-align: right;">{{ formatPrice(item.tapprop) }}</td>
                                        <td style="text-align: right;">{{ formatPrice(item.tallot) }}</td>
                                        <td style="text-align: right;">{{ formatPrice(item.toblig) }}</td>
                                        <td style="text-align: right;">{{ formatPrice(appropriationBalance(data, index)) }}</td>
                                        <td style="text-align: right;">{{ formatPrice(allotmentBalance(data, index)) }}</td>
                                    </tr>
                                </tbody>
                            </datatable>
                            <pagination
                                :pagination="pagination"
                                @prev="getData(pagination.prevPageUrl)"
                                @next="getData(pagination.nextPageUrl)"
                                v-if="pagination.total > tableData.length"
                            ></pagination>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#modalRaaodsLedger .modal-content {
    background-color: mistyrose;
}
/* 
Generic Styling, for Desktops/Laptops 
*/
table { 
  width: 100%; 
  border-collapse: collapse; 
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}
/* 
Max width before this PARTICULAR table gets nasty
This query will take effect for any screen smaller than 760px
and also iPads specifically.
*/
@media
	  only screen 
    and (max-width: 760px), (min-device-width: 768px) 
    and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}

    tr {
      margin: 0 0 1rem 0;
    }
      
    tr:nth-child(odd) {
      background: #ccc;
    }
    
		td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
		}

		td:before {
			/* Now like a table header */
			/* position: absolute; */
			/* Top/left values mimic padding */
			top: 0;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
            float: left;
            text-align: left;
		}
	
	/*
	Label the data
	*/
	.ledger td:nth-of-type(1):before { content: "Date: "; }
	.ledger td:nth-of-type(2):before { content: "Particulars: "; }
	.ledger td:nth-of-type(3):before { content: "Payee: "; }
	.ledger td:nth-of-type(4):before { content: "OBR#: "; }
	.ledger td:nth-of-type(5):before { content: "Appropriation: "; }
	.ledger td:nth-of-type(6):before { content: "Allotment: "; }
	.ledger td:nth-of-type(7):before { content: "Obligation: "; }
	.ledger td:nth-of-type(8):before { content: "Approp Balance: "; }
	.ledger td:nth-of-type(9):before { content: "Allot Balance: "; }
}
</style>

<script>
import Datatable from "../helpers/datatableLedger.vue";
import Pagination from "../helpers/pagination";



export default {
    props: ["recid"],
    components: {
        datatable: Datatable,
        pagination: Pagination
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "10%", label: "Date", name: "fdate" },
            { width: "10%", label: "Particulars", name: "fparticulars" },
            { width: "10%", label: "Payee", name: "payee" },
            { width: "10%", label: "OBR#", name: "frefno" },
            { width: "10%", label: "Appropriation", name: "tapprop" },
            { width: "10%", label: "Allotment", name: "toblig" },
            { width: "10%", label: "Obligation", name: "tallot" },
            {
                width: "10%",
                label: "Appropriation Balance",
                name: "appbalance"
            },
            { width: "10%", label: "Allotment Balance", name: "allbalance" }
        ];

        columns.forEach(column => {
            sortOrders[column.name] = -1;
        });

        return {
            columns: columns,
            sortKey: "recid",
            sortOrders: sortOrders,
            tableData: {
                draw: 0,
                length: 50,
                search: "",
                column: 0,
                dir: "desc"
            },
            pagination: {
                lastPage: "",
                currentPage: "",
                total: "",
                lastPageUrl: "",
                nextPageUrl: "",
                prevPageUrl: "",
                from: "",
                to: ""
            },
            data: [],
            runningAllot: []
        };
    },
    watch: {
        recid: function() {
            var self = this;
            self.tableData.idooe = self.recid.ooe;
            self.tableData.idraao = self.recid.raao;
            return this.getData();
        }
    },
    methods: {
        configPagination(data) {
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },
        sortBy(key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            this.tableData.column = this.getIndex(this.columns, "name", key);
            this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
            this.getData();
        },

        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value);
        },

        getData(url = "raaods-ledger") {
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data;
                    this.configPagination(data.data);
                }
            });
        },
        //formating number to currency
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        entryType(item) {
            if (item == 0) {
                return "RAAO DET";
            } else if (item == 1) {
                return "Approp";
            } else if (item == 2) {
                return "Allot";
            } else if (item == 3) {
                return "Oblig";
            }
        },

        appropriationBalance(data, index) {
            var firstBalance = data[0].tapprop - data[0].tallot;
            var newBalance = 0;
            var arr = [];
            _.forEach(data, function(e, index) {
                newBalance = newBalance + data[index].tapprop - data[index].tallot;
                arr.push(newBalance);
            });

            return arr[index];
        },

        allotmentBalance(data, index) {
            var initialBalance = data[1].tallot - data[1].toblig;
            var allotmentBalance = 0;
            var arr = [];
            _.forEach(data, function(e, index) {
                allotmentBalance = allotmentBalance + data[index].tallot - data[index].toblig ;
                arr.push(allotmentBalance);
                arr[0] = 0.00
            });

           return arr[index];
        }
    }
};
</script>
