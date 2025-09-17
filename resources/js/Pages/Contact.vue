<script setup>
import SeoHead from '@/Components/SeoHead.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, router } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    topic: '',
    message: '',
    link: '',
    website: '', // honeypot
})

function submit(){
    form.post('/contact', {
        onSuccess: () => { form.reset('message','link'); },
    })
}
</script>

<template>
    <AppLayout>
        <SeoHead title="Contact — Michael Stoffer" description="Get in touch about software, music, or photography." />

        <section class="pt-12 sm:pt-16 pb-8 max-w-2xl">
            <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">Contact</h1>
            <p class="mt-4 text-lg text-slate-700">I read and reply to every note. Tell me a little about what you need and I’ll get back to you.</p>

            <form @submit.prevent="submit" class="mt-8 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-900">Name</label>
                    <input v-model="form.name" type="text" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0" />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900">Email</label>
                    <input v-model="form.email" type="email" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900">Topic</label>
                    <select v-model="form.topic" class="mt-1 w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0">
                        <option value="">Select a topic…</option>
                        <option>Software</option>
                        <option>Music</option>
                        <option>Photography</option>
                        <option>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900">Message</label>
                    <textarea v-model="form.message" required rows="6" class="mt-1 w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0"></textarea>
                    <p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-900">(Optional) Link to brief/spec</label>
                    <input v-model="form.link" type="url" placeholder="https://…" class="mt-1 w-full rounded-lg border-slate-300 focus:border-slate-400 focus:ring-0" />
                    <p v-if="form.errors.link" class="mt-1 text-sm text-red-600">{{ form.errors.link }}</p>
                </div>

                <!-- Honeypot -->
                <div class="hidden" aria-hidden="true">
                    <label>Website</label>
                    <input v-model="form.website" type="text" tabindex="-1" autocomplete="off" />
                </div>

                <div class="pt-2">
                    <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 text-white px-4 py-2 text-sm font-medium shadow-soft hover:opacity-95 disabled:opacity-60">{{ form.processing ? 'Sending…' : 'Send message' }}</button>
                </div>

                <p v-if="$page.props.flash?.ok" class="mt-2 text-green-700">{{ $page.props.flash.msg }}</p>
            </form>

            <!-- Location / other ways -->
            <div class="mt-10 text-sm text-slate-600">
                <p>Based in <span class="font-medium">Myrtle Beach, SC</span></p>
                <p class="mt-1">Prefer email? <a href="mailto:mstoffer@michaelstoffer.com" class="hover:underline">mstoffer@michaelstoffer.com</a></p>
            </div>
        </section>
    </AppLayout>
</template>
