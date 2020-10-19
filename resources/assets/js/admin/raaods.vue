<template>
    <div>
        <div class="modal fade" id="exampleModalCenter">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Details</h5>
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
                                    :table_name="table_name"
                                >
                                    <tbody>
                                        <tr
                                            v-for="(item, index) in data"
                                            :key="item.recid"
                                            
                                        >
                                            <td class="details-control-raaods Action"></td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="FACTCODE">{{ item.ooe.FACTCODE }}</td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="FOOEDESC">{{ item.FOOEDESC }}</td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="tapprop" style="text-align: right;">{{ formatPrice(item.tapprop) }}</td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="tallot" style="text-align: right;">{{ formatPrice(item.tallot) }}</td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="toblig" style="text-align: right;">{{ formatPrice(item.toblig) }}</td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="Balance2" style="text-align: right;">{{ formatPrice(item.tapprop - item.tallot) }}</td>
                                            <td @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)" class="Balance1" style="text-align: right;">{{ formatPrice(item.tallot - item.toblig) }}</td>
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
        <raaods-ledger :recid="raaodId"></raaods-ledger>
    </div>
</template>
<style>
@media
	  only screen 
    and (max-width: 760px), (min-device-width: 768px) 
    and (max-device-width: 1024px)  {

	.FACTCODE,.tapprop,.tallot,.toblig,.Balance2,.Balance1 {
        display: none;
    }

    .child_table_raaods td:first-child{
        width: 10%;
    }
}
</style>
<script>
import Datatable from "../helpers/datatable.vue";
import Pagination from "../helpers/pagination";
import RaaodsLedger from "../admin/raaodsLedger";

export default {
    props: ["recid"],
    components: {
        datatable: Datatable,
        pagination: Pagination,
        RaaodsLedger
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "10%", label: "Action", name: "Action" },
            { width: "10%", label: "Account Code", name: "FACTCODE" },
            {
                width: "50%",
                label: "Account Description",
                name: "FOOEDESC"
            },
            { width: "10%", label: "Appropration", name: "tapprop" },
            { width: "10%", label: "Allotment", name: "tallot" },
            { width: "10%", label: "Obligation", name: "toblig" },
            { width: "10%", label: "Appropriation Balance", name: "Balance2" },
            { width: "10%", label: "Allotment Balance", name: "Balance1" }
        ];

        columns.forEach(column => {
            sortOrders[column.name] = -1;
        });

        return {
            table_name: "raaods",
            columns: columns,
            sortKey: "recid",
            sortOrders: sortOrders,
            tableData: {
                draw: 0,
                length: 10,
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
            raaodId: ""
        };
    },
    watch: {
        recid: function() {
            var self = this;
            self.tableData.recid = self.recid;

            return this.getData();
        }
    },
    updated() {
        if ($(window).width() > 700) {
            $('.Action').addClass('d-none')
        }
    },
    mounted() {
        

        // Add event listener for opening and closing details
        if ($(window).width() < 700) {

        //    $('.raaoh td:nth-child(3)').hide()

            var toggle_desc_raods = false
            var self = this
            $('.raaods tbody').on('click', 'td.details-control-raaods', function () {
                    $(this).addClass('shown')
                    var tr = $(this).closest('tr')
                    toggle_desc_raods = !toggle_desc_raods

                    if (toggle_desc_raods) {
                        if ($(window).width() < 700) 
                        tr.after('<tr class="child_raods">' +
                                    '<td colspan="2">'+ 
                                        '<table class="child_table_raaods"><tbody>'+
                                            '<tr>'+
                                                '<td>'+ $('.raaods').find("th:nth-child(2)").html() +'</td>' +
                                                '<td>'+ tr.find("td:nth-child(2)").html() +'</td>' +
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+ $('.raaods').find("th:nth-child(4)").html() +'</td>' +
                                                '<td>'+ tr.find("td:nth-child(4)").html() +'</td>' +
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+ $('.raaods').find("th:nth-child(5)").html() +'</td>' +
                                                '<td>'+ tr.find("td:nth-child(5)").html() +'</td>' +
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+ $('.raaods').find("th:nth-child(6)").html() +'</td>' +
                                                '<td>'+ tr.find("td:nth-child(6)").html() +'</td>' +
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+ $('.raaods').find("th:nth-child(7)").html() +'</td>' +
                                                '<td>'+ tr.find("td:nth-child(7)").html() +'</td>' +
                                            '</tr>'+
                                            '<tr>'+
                                                '<td>'+ $('.raaods').find("th:nth-child(8)").html() +'</td>' +
                                                '<td>'+ tr.find("td:nth-child(8)").html() +'</td>' +
                                            '</tr>'+
                                        '</tbody></table>' +
                                    '</td>' +
                                '</tr>');


                    } else {
                         $(this).removeClass('shown')
                        $('.child_raods').remove()
                    }
                
            } );

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

        getData(url = "raaods") {
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
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
        showRaaodsLedger(recId, ooeId, raaoId) {
            this.raaodId = { raaod: recId, ooe: ooeId, raao: raaoId };

            $("#modalRaaodsLedger").modal("show");
        }
    }
};
</script>
