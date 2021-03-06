<template>
  <div v-if="user.provider && provider && POSTS.data">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-12 col-md-4 text-center">
          <img
            :src="user.provider.avatar"
            class="w-75 shadow-lg rounded"
            :alt="`${user.name} profile picture`"
          />
        </div>
        <div class="col-12 col-md-8 mt-3 mt-md-0">
          <div class="card bg-primary">
            <div class="card-body">
              <h4>{{ user.name }}</h4>
              <h5>{{ provider.login }}</h5>
              <p class="mb-0">
                <em>"{{ provider.bio }}"</em>
              </p>
              <button class="btn btn-outline-info float-end mt-3">
                <a
                  :href="provider.url"
                  class="text-decoration-none text-white d-flex align-items-center"
                  target="_blank"
                  ><i class="ri-github-fill ri-lg me-2"></i
                  ><span class="align-bottom">Github Profile</span></a
                >
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="currentUser">
        <div class="row mt-4">
          <div class="col">
            <button class="btn btn-outline-warning w-100 m-1">
              Settings
            </button>
            <button class="btn btn-outline-danger w-100 m-1" @click="signOut">
              Sign Me Out
            </button>
          </div>
        </div>
      </div>
      <hr />
      <div class="row mt-4">
        <div class="col-12">
          <h1>{{ user.name }}'s Posts</h1>
        </div>
        <div v-if="currentUser">
          <UserProfilePostCreate />
          <hr class="mt-4" />
        </div>
      </div>
      <div v-if="POSTS.data.length > 0" class="row mt-4">
        <div
          v-for="(post, index) in POSTS.data"
          :key="index"
          class="col-12 col-md-6 col-lg-4"
        >
          <div class="card bg-primary">
            <div class="card-body">
              <h3>{{ post.title }}</h3>
              <p class="text-truncate">{{ post.description }}</p>
              <NuxtLink
                :to="`/posts/${post.id}/${post.slug}`"
                class="btn btn-sm btn-info float-end text-white"
              >
                Read More ...
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="row mt-4">
        <div class="col text-center">
          <p class="text-white">Write your first amazing post!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
  name: 'UserProfile',
  data() {
    return {
      user: {},
      provider: {}
    }
  },
  async fetch() {
    try {
      this.UPDATE_LOADING()
      if (this.$route.params.id !== this.loggedInUser?.id) {
        try {
          await this.GET_USER(this.$route.params.id)
          this.user = this.USER.data
        } catch (err) {
          console.log(err)
          this.$toast.error(
            'Sorry! Something went wrong. Please try again later.'
          )
        }
      } else {
        this.user = this.loggedInUser
      }

      await this.getUserProviderData()
      await this.GET_POSTS({ user_id: this.user.id })
    } catch (err) {
      this.$toast.error('Sorry! Something went wrong. Please try again later.')
    } finally {
      this.UPDATE_LOADING(false)
    }
  },
  computed: {
    ...mapGetters({
      loggedInUser: 'loggedInUser',
      USER: 'users/USER',
      POSTS: 'posts/POSTS'
    }),
    currentUser() {
      return this.user.id == this.loggedInUser?.id
    }
  },

  methods: {
    ...mapActions({
      GET_USER: 'users/GET_USER',
      GET_POSTS: 'posts/GET_POSTS',
      UPDATE_LOADING: 'UPDATE_LOADING'
    }),
    async signOut() {
      await this.$auth.logout()
      this.$router.push({ path: '/' })
    },
    async getUserProviderData() {
      try {
        const res = await this.$axios.$get(
          `https://api.github.com/user/${this.user.provider.provider_id}`
        )
        this.provider = res
      } catch (err) {
        this.$toast.error('Fail to fetch provider data')
      }
    }
  }
}
</script>

<style></style>
