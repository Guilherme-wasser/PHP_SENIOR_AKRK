import { defineStore } from 'pinia'
import api from '@/services/api'

export const useImportStore = defineStore('imports', {
  state: () => ({
    list: [],           // Lista dos processamentos da página atual
    pagination: {       // Dados de paginação do Laravel
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0,
    },
    filters: {          // Novos filtros: status, datas, etc.
      status: '',
      date_from: '',
      date_to: '',
    },
  }),

  actions: {
    /**
     * Busca lista com filtros + paginação
     * Exemplo de uso:
     *   store.fetch()         // usa filtros atuais
     *   store.fetch({ page: 2 })  // muda página
     */
    async fetch(extraParams = {}) {
      const params = {
        ...this.filters,           // Aplica filtros fixos do estado
        page: this.pagination.current_page,
        per_page: this.pagination.per_page,
        ...extraParams,            // Sobrescreve se precisar (ex: { page: 2 })
      }

      const { data } = await api.get('/imports', { params })

      this.list = data.data
      this.pagination = {
        ...data,    // Copia tudo que vem do Laravel (current_page, last_page, etc.)
        data: undefined, // Remove data duplicado
      }
    },

    /**
     * Atualiza um filtro e faz a busca de novo
     */
    async setFilter(name, value) {
      this.filters[name] = value
      this.pagination.current_page = 1  // Sempre volta pra página 1 ao filtrar
      await this.fetch()
    },

    /**
     * Troca de página
     */
    async goToPage(page) {
      this.pagination.current_page = page
      await this.fetch()
    },

    /**
     * Cria nova importação (continua igual)
     */
    async create({ fund_id, sequence, file }) {
      const fd = new FormData()
      fd.append('fund_id', fund_id)
      fd.append('sequence', sequence)
      fd.append('file', file)
      return api.post('/imports', fd)
    },
  },
})
