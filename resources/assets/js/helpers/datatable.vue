<template>
    <div class="table-responsive">
        <table :class="'table table-hover table-sm ' + table_name">
            <thead>
                <tr>
                    <th v-for="column in columns"
                        :key="column.name"
                        @click="$emit('sort', column.name)" 
                        :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting' + column.name +' '+column.name"
                        :style="'width:'+column.width+'; cursor:pointer;'"
                    >
                        {{ column.label}}
                    </th>
                </tr>
            </thead>
            <slot></slot>
        </table>
    </div>
</template>

<style>
.table {
    margin-bottom: 1.5rem;
}

.table td, .table th{
    line-height: normal;
}
</style>

<script>
    export default {
        props: ['columns', 'sortKey', 'sortOrders', 'table_name']
    }
</script>