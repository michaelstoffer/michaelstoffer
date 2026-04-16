---
title: Getting Started with Vue 3 Composables
excerpt: Composables are one of the most powerful patterns in Vue 3 — they let you extract and reuse stateful logic across components without the mess of mixins or repeated code.
cover: /media/blog/getting-started-with-vue-3-composables/cover.webp
published_at: "2026-04-14 08:00:00 -0400"
modified_at: "2026-04-14 08:00:00 -0400"
tags: ["Frontend", "Development", "JavaScript"]
---

If you've been working with Vue 3 for a while, you've probably heard the word "composable" thrown around quite a bit. And for good reason — composables are one of the most useful patterns the Composition API introduced. They let you extract reusable, stateful logic out of your components and share it across your app without duplicating code or wrestling with the limitations of mixins.

In this post, I want to walk you through what composables are, when to reach for them, and how to write one from scratch.

## What Is a Composable?

A composable is a plain JavaScript (or TypeScript) function that uses Vue's reactivity APIs — `ref`, `reactive`, `computed`, `watch`, and so on — and follows the convention of starting with `use`. You've probably seen names like `useRouter` or `useHead` in the wild. Your own might be `useCounter`, `useFetch`, or `useAuth`.

The key thing that separates a composable from a regular utility function is that it can hold and return <strong>reactive state</strong>. Call it inside any `<script setup>` block and it plugs directly into Vue's reactivity system.

If you worked with Vue 2, you might be thinking: "Isn't this just mixins with extra steps?" Not quite. Mixins had real problems — it was hard to tell where a property came from, naming collisions were common, and debugging was a headache. Composables solve all of that by being explicit: you call a function, you see exactly what it returns, and you name the values yourself at the call site.

## A Simple Example: useCounter

Let's start with something classic to see the shape of a composable:

<pre><code>
// composables/useCounter.js
import { ref } from 'vue';

export function useCounter(initialValue = 0) {
    const count = ref(initialValue);

    function increment() {
        count.value++;
    }

    function decrement() {
        count.value--;
    }

    function reset() {
        count.value = initialValue;
    }

    return { count, increment, decrement, reset };
}
</code></pre>

Using it in a component looks like this:

<pre><code>
&lt;script setup&gt;
import { useCounter } from '@/composables/useCounter';

const { count, increment, decrement, reset } = useCounter(5);
&lt;/script&gt;

&lt;template&gt;
    &lt;p&gt;Count: {{ count }}&lt;/p&gt;
    &lt;button @click="increment"&gt;+&lt;/button&gt;
    &lt;button @click="decrement"&gt;-&lt;/button&gt;
    &lt;button @click="reset"&gt;Reset&lt;/button&gt;
&lt;/template&gt;
</code></pre>

The composable owns the logic. The component just consumes what it needs. Clean separation, no clutter.

## A More Practical Example: useFetch

A counter is great for illustration, but here's something you'd actually reach for on a real project — a composable that handles data fetching:

<pre><code>
// composables/useFetch.js
import { ref } from 'vue';

export function useFetch(url) {
    const data = ref(null);
    const error = ref(null);
    const loading = ref(false);

    async function fetchData() {
        loading.value = true;
        error.value = null;

        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            data.value = await response.json();
        } catch (err) {
            error.value = err.message;
        } finally {
            loading.value = false;
        }
    }

    fetchData();

    return { data, error, loading };
}
</code></pre>

Now any component that needs to hit an endpoint can do this:

<pre><code>
&lt;script setup&gt;
import { useFetch } from '@/composables/useFetch';

const { data: posts, error, loading } = useFetch('/api/posts');
&lt;/script&gt;

&lt;template&gt;
    &lt;div v-if="loading"&gt;Loading...&lt;/div&gt;
    &lt;div v-else-if="error"&gt;{{ error }}&lt;/div&gt;
    &lt;ul v-else&gt;
        &lt;li v-for="post in posts" :key="post.id"&gt;{{ post.title }}&lt;/li&gt;
    &lt;/ul&gt;
&lt;/template&gt;
</code></pre>

Notice how destructuring assignment lets you rename `data` to `posts` right at the call site. That's one of the nicest ergonomic wins with composables — the same function can be used for different resources without any naming conflicts.

## When Should You Write a Composable?

Not everything needs to become a composable. My rule of thumb: if you find yourself copying stateful logic between two or more components, extract it. Some common use cases worth pulling into composables:

<li><strong>API calls and data fetching</strong> — loading states, error handling, response data</li>
<li><strong>Form handling</strong> — validation, dirty tracking, submission state</li>
<li><strong>Browser APIs</strong> — scroll position, window resize, intersection observers, local storage</li>
<li><strong>Authentication state</strong> — current user, permissions, session checks</li>
<li><strong>UI state</strong> — modals, toasts, dropdown visibility, dark mode toggles</li>

If the logic is purely stateless — like a `formatDate` helper or a string utility — a regular function is the right call. You don't need reactivity for that.

## How to Organize Your Composables

I keep composables in a dedicated `composables/` folder, usually inside `resources/js/` for Laravel projects:

<pre><code>
resources/js/
  composables/
    useAuth.js
    useFetch.js
    useWindowSize.js
    useToast.js
</code></pre>

The file name matches the exported function name, which makes them easy to find and import. As a project grows, this folder becomes one of the most valuable parts of the codebase — a library of reusable logic that your whole team can pull from.

## One Important Rule: Call Composables at the Top Level

Vue's reactivity system needs composables to be called synchronously at the top level of `setup()` or `<script setup>`. Don't call them inside `v-if` conditionals, event handlers, or nested functions. This is the same constraint as React's hooks rule, and for the same reason — Vue needs to track reactive dependencies in a consistent order.

<pre><code>
// ✅ Correct — called at the top level
const { count, increment } = useCounter();

// ❌ Wrong — called inside a conditional
if (someCondition) {
    const { count } = useCounter(); // Don't do this
}
</code></pre>

## Final Thoughts

Composables are one of those features that, once you start using them, you'll wonder how you ever managed without them. They make components leaner, push logic into testable units, and give your whole team a shared vocabulary for common patterns.

Start small — pick one piece of repeated logic in your current project, extract it into a composable, and see how it feels. My guess is it'll become second nature pretty quickly.
