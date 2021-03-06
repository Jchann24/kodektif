<template>
  <div>
    <div class="container-fluid">
      <div class="row">
        <div class="col d-flex justify-content-between">
          <div>
            <div v-if="currentChapter.user_chapter_done" class="text-success">
              <i class="ri-checkbox-circle-line ri-xl "></i>
              <span>You have done this chapter</span>
            </div>
            <div v-else class="text-danger">
              <i class="ri-close-circle-line ri-xl text-danger"></i>
              <span>You have not done this chapter</span>
            </div>
          </div>
          <div>
            <h5>{{ doc.order + 1 }} / {{ COURSE.chapter_count }}</h5>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12" :class="{ 'col-lg-6': !isIntroOrFinish }">
          <div :class="{ container: isIntroOrFinish }">
            <nuxt-content
              :document="doc"
              :class="{ 'content-style': !isIntroOrFinish }"
            />
          </div>
        </div>
        <div
          class="col-12 col-lg-6 border-start border-primary"
          :class="{ 'd-none': isIntroOrFinish }"
        >
          <CoursesCodeEditor :document="doc" @evaluated="codeEvaluated" />
        </div>
      </div>
      <div v-if="COURSE" class="row mt-5">
        <div
          class="col-11 mx-auto d-flex align-items-center justify-content-between"
        >
          <div v-if="getPrevChapter()">
            <NuxtLink
              :to="
                `/courses/${COURSE.id}/${COURSE.slug}/${getPrevChapter().slug}`
              "
              type="button"
              class="btn btn-primary text-decoration-none text-white d-flex align-items-center"
            >
              <i class="ri-arrow-left-s-line me-1"></i>
              <div>Previous chapter: {{ getPrevChapter().title }}</div>
            </NuxtLink>
          </div>
          <div v-else></div>
          <div v-if="getNextChapter().slug == 'done'">
            <NuxtLink
              :to="`/courses`"
              type="button"
              class="btn btn-primary text-decoration-none text-white d-flex align-items-center"
              @click.native="handleNextChapter()"
            >
              <div>{{ getNextChapter().title }}</div>
              <i class="ri-arrow-right-s-line ms-1"></i>
            </NuxtLink>
          </div>
          <div v-else>
            <NuxtLink
              :to="
                `/courses/${COURSE.id}/${COURSE.slug}/${getNextChapter().slug}`
              "
              type="button"
              class="btn btn-primary text-decoration-none text-white d-flex align-items-center"
              @click.native="handleNextChapter()"
            >
              <div>Next chapter: {{ getNextChapter().title }}</div>
              <i class="ri-arrow-right-s-line ms-1"></i>
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'CourseChapter',
  async asyncData({ $content, app, route }) {
    const doc = await $content(
      `${app.i18n.localeProperties.code}/${route.params.course}/${route.params.chapter}`,
      {
        deep: true
      }
    ).fetch()
    return { doc }
  },

  async fetch() {
    await this.GET_COURSE(this.$route.params.id)
  },
  head() {
    return {
      title: `${this.doc.chapter} - ${this.doc.course}`,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: `${this.doc.description}`
        }
      ]
    }
  },
  computed: {
    ...mapGetters({
      COURSE: 'courses/COURSE',
      isAuthenticated: 'isAuthenticated'
    }),
    isIntroOrFinish() {
      if (!Object.keys(this.COURSE)) {
        return true
      }
      let chapterOrder = this.COURSE.chapters.find((obj) => {
        return obj.slug == this.$route.params.chapter
      })

      return chapterOrder.order == 0 || chapterOrder.slug == 'finish'
    },
    currentChapter() {
      return this.COURSE.chapters.find((x) => x.slug == this.doc.slug)
    }
  },
  methods: {
    ...mapActions({
      GET_COURSE: 'courses/GET_COURSE',
      DONE_CHAPTER_IN_COURSE: 'courses/DONE_CHAPTER_IN_COURSE'
    }),
    async handleNextChapter() {
      if (
        (this.doc.order === 0 || this.doc.chapter == 'Finish') &&
        this.currentChapter.user_chapter_done === null
      ) {
        if (this.isAuthenticated) {
          const res = await this.$axios.$post('chapter-answers', {
            chapter_id: this.currentChapter.id,
            answer: this.doc.order === 0 ? 'intro done' : 'finish chapter'
          })
          console.log(res)
        }
      }
    },
    async codeEvaluated(val) {
      if (!this.isAuthenticated) {
        this.$toast.info('Please login to save your answer.')
        return
      }

      if (!val.pass) {
        this.$toast.info('Tests failing, please try again.')
      }

      let res
      if (val.pass && !this.currentChapter.user_chapter_done) {
        try {
          res = await this.$axios.$post('chapter-answers', {
            chapter_id: this.currentChapter.id,
            answer: val.code
          })

          this.$toast.success(
            'Success saving your answer, you can go to the next chapter'
          )
        } catch (err) {
          this.$toast.error('Something went wrong when submitting your answer')
        }

        const payload = {
          chapterId: res.data.chapter_id,
          data: res.data
        }

        this.DONE_CHAPTER_IN_COURSE(payload)
      } else if (val.pass && this.currentChapter.user_chapter_done) {
        try {
          res = await this.$axios.$patch(
            'chapter-answers/' + this.currentChapter.user_chapter_done.id,
            {
              chapter_id: this.currentChapter.id,
              answer: val.code
            }
          )

          this.$toast.success(
            'Success saving your answer, you can go to the next chapter'
          )
        } catch (err) {
          this.$toast.error('Something went wrong when submitting your answer')
        }

        const payload = {
          chapterId: res.data.chapter_id,
          data: res.data
        }

        this.DONE_CHAPTER_IN_COURSE(payload)
      }
    },

    getNextChapter() {
      const currentOrder = this.doc.order
      const chapters = this.COURSE.chapters
      let nextChapter = chapters.find((x) => {
        return x.order == currentOrder + 1
      })
      if (!nextChapter) {
        nextChapter = {
          title: 'Done',
          slug: 'done'
        }
      }
      return nextChapter
    },
    getPrevChapter() {
      const currentOrder = this.doc.order
      const chapters = this.COURSE.chapters
      let prevChapter = chapters.find((x) => {
        return x.order == currentOrder - 1
      })
      if (!prevChapter) {
        prevChapter = false
      }
      return prevChapter
    }
  }
}
</script>

<style>
.nuxt-content-highlight {
  max-width: 95%;
  margin: auto;
}

.nuxt-content code {
  color: #f67e7d;
}

.content-style {
  height: 85vh;
  overflow: auto;
}
</style>
