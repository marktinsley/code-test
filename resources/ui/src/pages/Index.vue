<template>
  <q-page class="q-pa-lg">
    <q-table
      :rows="rows"
      :columns="columns"
      row-key="id"
      v-model:pagination="pagination"
      :loading="loading"
      :filter="filter"
      @request="onRequest"
      binary-state-sort
      :rows-per-page-options="[ 3, 5, 7, 10, 15, 20, 25, 50 ]"
    >
      <template v-slot:top>
        <q-toggle
          label="Only my products"
          v-model="onlyMine"
        />
        <q-space/>
        <q-input borderless dense debounce="300" color="primary" v-model="filter">
          <template v-slot:append>
            <q-icon name="search"/>
          </template>
        </q-input>
      </template>

      <template v-slot:top-right>
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search"/>
          </template>
        </q-input>
      </template>

    </q-table>
  </q-page>
</template>

<script>
import { onMounted, ref, watchEffect } from 'vue'
import { api } from 'boot/axios'

const columns = [
  {
    name: 'name',
    required: true,
    label: 'Name',
    align: 'left',
    field: 'name',
    sortable: true
  },
  {
    name: 'price',
    label: 'Price',
    field: 'price',
    sortable: true,
    format: (value) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value / 100)
  }
]

export default {
  setup () {
    const rows = ref([])
    const filter = ref('')
    const loading = ref(false)
    const onlyMine = ref(false)
    const pagination = ref({
      sortBy: 'desc',
      descending: false,
      page: 1,
      rowsPerPage: 25,
      rowsNumber: 10
    })

    async function onRequest (props) {
      const { page, rowsPerPage, sortBy, descending } = props.pagination
      const filter = props.filter

      loading.value = true

      const response = await api.get(
        'api/products', {
          params: {
            per_page: rowsPerPage,
            page,
            filter: filter || '',
            sort_by: sortBy || '',
            descending: descending ? 1 : 0,
            only_mine: onlyMine.value ? 1 : 0
          }
        }
      )
      rows.value = response.data.data

      pagination.value.page = response.data.current_page
      pagination.value.rowsPerPage = response.data.per_page
      pagination.value.rowsNumber = response.data.total
      pagination.value.sortBy = sortBy
      pagination.value.descending = descending

      loading.value = false
    }

    onMounted(() => {
      // get initial data from server (1st page)
      onRequest({
        pagination: pagination.value,
        onlyMine: false,
        filter: undefined
      })
    })

    watchEffect(() => {
      onRequest({
        pagination: pagination.value,
        onlyMine: onlyMine.value,
        filter: filter.value
      })
    })

    return {
      filter,
      loading,
      pagination,
      columns,
      rows,
      onlyMine,

      onRequest
    }
  }
}
</script>
