import { api } from 'boot/axios'

export async function login (context, credentials) {
  context.commit('setLoginMessage', null)

  try {
    await api.get('sanctum/csrf-cookie')
    await api.post('login', {
      email: credentials.username,
      password: credentials.password
    })
    const response = await api.get('api/user')

    context.commit('setUser', response.data)
  } catch (error) {
    context.commit('setLoginMessage', error.response.data.message)
  }
}

export async function logout (context) {
  await api.get('sanctum/csrf-cookie')
  await api.post('logout')

  context.commit('setUser', null)
}
