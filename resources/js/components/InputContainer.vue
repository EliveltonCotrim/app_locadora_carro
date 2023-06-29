<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{ title }}</label>
        <slot></slot>
        <div :id="idHelp" class="form-text text-muted">{{ helpText }}</div>
    </div>
</template>
<script>

export default {
    name: 'InputContainer',
    props: ['title', 'id', 'helpText', 'idHelp']
}

</script>
