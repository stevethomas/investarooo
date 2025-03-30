<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

defineProps({
    holdings: Array,
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const cost = (holding) => Number(holding.units) * Number(holding.price);
const marketValue = (holding) => Number(holding.units) * Number(holding.last_price);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Table>
                    <TableCaption>Current holdings</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                Ticker
                            </TableHead>
                            <TableHead>Units</TableHead>
                            <TableHead>Purchase</TableHead>
                            <TableHead>Last</TableHead>
                            <TableHead>Cost</TableHead>
                            <TableHead>Market Value</TableHead>
<!--                            <TableHead class="text-right">Profit / Loss</TableHead>-->
<!--                            <TableHead class="text-right">Weight</TableHead>-->
<!--                            <TableHead class="text-right">Yield</TableHead>-->
<!--                            <TableHead class="text-right">DRP Weight</TableHead>-->
                            <TableHead>Frequency</TableHead>
                            <TableHead>Registry</TableHead>
                            <TableHead>Notes</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="holding in holdings" :key="holding.id">
                            <TableCell class="font-medium">
                                {{ holding.ticker }}
                            </TableCell>
                            <TableCell class="text-right">{{ holding.units }}</TableCell>
                            <TableCell class="text-right">{{ holding.price }}</TableCell>
                            <TableCell class="text-right">{{ holding.last_price }}</TableCell>
                            <TableCell class="text-right">{{ cost(holding) }}</TableCell>
                            <TableCell class="text-right">{{ marketValue(holding) }}</TableCell>
                            <TableCell>{{ holding.dividend_frequency }}</TableCell>
                            <TableCell>{{ holding.registry }}</TableCell>
                            <TableCell>{{ holding.notes }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
