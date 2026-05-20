import { ref } from 'vue'

export function useFormValidation(rules) {
    const touched = ref({})
    const errors = ref({})

    const validate = () => {
        const next = {}
        for (const [field, rule] of Object.entries(rules)) {
            const result = rule()
            if (result) next[field] = result
        }
        errors.value = next
        return Object.keys(next).length === 0
    }

    const touch = (field) => {
        touched.value[field] = true
        validate()
    }

    const markAllTouched = () => {
        for (const field of Object.keys(rules)) {
            touched.value[field] = true
        }
        return validate()
    }

    const reset = () => {
        touched.value = {}
        errors.value = {}
    }

    const error = (field) => (touched.value[field] ? errors.value[field] : null)

    return { touched, errors, touch, validate, markAllTouched, reset, error }
}
