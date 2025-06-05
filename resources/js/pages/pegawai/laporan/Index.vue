<script setup lang="ts">
import { Tooltip, TooltipTrigger, TooltipContent, TooltipProvider } from '@/components/ui/tooltip'
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Arahkan ke sini</button>
            </TooltipTrigger>
            <TooltipContent>
                Ini adalah tooltip!
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>