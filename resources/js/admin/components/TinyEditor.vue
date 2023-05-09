<template>
    <div>
        <Editor
            v-model="value"
            api-key="3prlpp08gavbzmbpqlvnt6eiyjbow10fyxawgpgf4n3wcei3"
            :init="{
                 height: 500,
                 menubar: false,
                 plugins: [
                   'advlist autolink lists link image charmap print preview anchor',
                   'searchreplace visualblocks code fullscreen',
                   'insertdatetime media table paste code help wordcount'
                 ],
                 toolbar:
                   'undo redo | formatselect | bold italic backcolor | \
                   alignleft aligncenter alignright alignjustify | \
                   bullist numlist outdent indent | removeformat | help'
            }"
        />
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'

export default {
    name: 'TinyEditor',
    components: {
        Editor,
    },
    props: {
        val: {
            type: String,
            default() {
                return ''
            }
        }
    },
    data() {
        return {
            value: this.val ?? ''
        }
    },
    mounted() {
        console.log(this.val)
    }
}
</script>
