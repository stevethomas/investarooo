<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger
} from '@/components/ui/tooltip'
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Deferred, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import Skeleton from '../components/Skeleton.vue';

const props = defineProps({
    holdings: Array,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const number = (amount: number, style: string = 'decimal', places: number = 0) =>
    new Intl.NumberFormat('en-AU', {
        style: style,
        currency: 'AUD',
        minimumFractionDigits: places,
        maximumFractionDigits: places,
    }).format(amount);

const price = (amount: number, places: number = 0) => number(amount, 'currency', places);

const cost = (holding) => Number(holding.units) * Number(holding.purchase);
const marketValue = (holding) => Number(holding.units) * Number(holding.last_price);
const profitLossAmount = (holding) => marketValue(holding) - cost(holding);
const profitLoss = (holding) => profitLossAmount(holding) / marketValue(holding);
const weight = (holding) => marketValue(holding) / aggregates.value.marketValue;
const yieldAmount = (holding) => marketValue(holding) * (holding.yield / 100);

const greenRed = (value: number) => {
    return {
        'text-green-500': value > 0,
        'text-red-500': value < 0,
    };
};

const aggregates = computed(() => {
    return {
        units: props.holdings.reduce((acc, holding) => acc + holding.units, 0),
        cost: props.holdings.reduce((acc, holding) => acc + cost(holding), 0),
        marketValue: props.holdings.reduce((acc, holding) => acc + marketValue(holding), 0),
        profitLossAmount: props.holdings.reduce((acc, holding) => acc + profitLossAmount(holding), 0),
        profitLoss:
            props.holdings.reduce((acc, holding) => acc + profitLossAmount(holding), 0) /
            props.holdings.reduce((acc, holding) => acc + marketValue(holding), 0),
        yieldAmount: props.holdings.reduce((acc, holding) => acc + yieldAmount(holding), 0),
        yieldPercentage:
            props.holdings.reduce((acc, holding) => acc + yieldAmount(holding), 0) /
            props.holdings.reduce((acc, holding) => acc + marketValue(holding), 0),
        drpAmount: props.holdings.reduce((acc, holding) => acc + yieldAmount(holding) * (holding.drp_weight / 100), 0),
        cashAmount: props.holdings.reduce((acc, holding) => acc + yieldAmount(holding) * (1 - holding.drp_weight / 100), 0),
        drpWeight: props.holdings.reduce((acc, holding) => acc + holding.drp_weight, 0) / props.holdings.length,
    };
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <Deferred data="holdings">
                    <Card>
                        <CardHeader>
                            <CardTitle>Total Return</CardTitle>
                            <CardDescription>Total portfolio returns</CardDescription>
                        </CardHeader>
                        <CardContent class="text-lg">
                            Total portfolio return is
                            <span :class="greenRed(aggregates.profitLossAmount)">
                                {{ price(aggregates.profitLossAmount) }}
                            </span>
                            <span> at </span>
                            <span :class="greenRed(aggregates.profitLoss)">
                                {{ number(aggregates.profitLoss, 'percent', 2) }}
                            </span>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Yield</CardTitle>
                            <CardDescription>Total yields over time periods</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4 divide-y text-lg">
                            <div>
                                Annual yield is
                                <span :class="greenRed(aggregates.yieldAmount)">
                                    {{ price(aggregates.yieldAmount) }}
                                </span>
                                <span> at </span>
                                <span :class="greenRed(aggregates.yieldPercentage)">
                                    {{ number(aggregates.yieldPercentage, 'percent', 2) }}
                                </span>

                                <br>

                                <span class="text-sm text-muted-foreground">
                                    <span :class="greenRed(aggregates.yieldAmount)">
                                        {{ price(aggregates.cashAmount) }}
                                    </span>
                                    as cash and
                                    <span :class="greenRed(aggregates.yieldAmount)">
                                        {{ price(aggregates.drpAmount) }}
                                    </span>
                                    is reinvested
                                </span>
                            </div>

                            <div class="pt-4">
                                Monthly yield is
                                <span :class="greenRed(aggregates.yieldAmount)">
                                    {{ price(aggregates.yieldAmount / 12) }}
                                </span>
                                <span> at </span>
                                <span :class="greenRed(aggregates.yieldPercentage)">
                                    {{ number(aggregates.yieldPercentage / 12, 'percent', 2) }}
                                </span>

                                <br />

                                <span class="text-sm text-muted-foreground">
                                    <span :class="greenRed(aggregates.yieldAmount)">
                                        {{ price(aggregates.cashAmount / 12) }}
                                    </span>
                                    as cash and
                                    <span :class="greenRed(aggregates.yieldAmount)">
                                        {{ price(aggregates.drpAmount / 12) }}
                                    </span>
                                    is reinvested
                                </span>
                            </div>

                            <div class="pt-4">
                                Weekly yield is
                                <span :class="greenRed(aggregates.yieldAmount)">
                                    {{ price(aggregates.yieldAmount / 52) }}
                                </span>
                                <span> at </span>
                                <span
                                    :class="{
                                        'text-green-500': aggregates.yieldPercentage > 0,
                                        'text-red-500': aggregates.yieldPercentage < 0,
                                    }"
                                >
                                    {{ number(aggregates.yieldPercentage / 52, 'percent', 2) }}
                                </span>

                                <br />

                                <span class="text-sm text-muted-foreground">
                                    <span :class="greenRed(aggregates.yieldAmount)">
                                        {{ price(aggregates.cashAmount / 52) }}
                                    </span>
                                    as cash and
                                    <span :class="greenRed(aggregates.yieldAmount)">
                                        {{ price(aggregates.drpAmount / 52) }}
                                    </span>
                                    is reinvested
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <template #fallback>
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                            <PlaceholderPattern />
                        </div>
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                            <PlaceholderPattern />
                        </div>
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                            <PlaceholderPattern />
                        </div>
                    </template>
                </Deferred>
            </div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> Ticker</TableHead>
                            <TableHead>Units</TableHead>
                            <TableHead>Purchase</TableHead>
                            <TableHead>Last</TableHead>
                            <TableHead>Cost</TableHead>
                            <TableHead>Market Value</TableHead>
                            <TableHead>Profit / Loss $</TableHead>
                            <TableHead>Profit / Loss %</TableHead>
                            <TableHead>Weight</TableHead>
                            <TableHead>Yield %</TableHead>
                            <TableHead>Yield $</TableHead>
                            <TableHead>DRP Weight</TableHead>
                            <TableHead>Frequency</TableHead>
                            <TableHead>Registry</TableHead>
                            <TableHead>Notes</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <Deferred data="holdings">
                            <template #fallback>
                                <TableCell v-for="i in Array(14).keys()" :key="i">
                                    <Skeleton />
                                </TableCell>
                            </template>

                            <TableRow v-for="holding in holdings" :key="holding.id">
                                <TableCell class="font-medium">
                                    <TooltipProvider>
                                        <Tooltip>
                                            <TooltipTrigger>{{ holding.ticker }}</TooltipTrigger>
                                            <TooltipContent>
                                                <p>{{ holding.name }}</p>
                                            </TooltipContent>
                                        </Tooltip>
                                    </TooltipProvider>
                                </TableCell>
                                <TableCell class="text-right">{{ number(holding.units) }}</TableCell>
                                <TableCell class="text-right">{{ price(holding.purchase, 3) }}</TableCell>
                                <TableCell class="text-right">{{ price(holding.last_price, 3) }}</TableCell>
                                <TableCell class="text-right">{{ price(cost(holding)) }}</TableCell>
                                <TableCell class="text-right">{{ price(marketValue(holding)) }}</TableCell>
                                <TableCell class="text-right">{{ price(profitLossAmount(holding)) }}</TableCell>
                                <TableCell class="text-right">{{ number(profitLoss(holding), 'percent', 2) }} </TableCell>
                                <TableCell
                                    class="text-right"
                                    :class="{
                                        'text-orange-500': weight(holding) > 0.2,
                                        'text-red-500': weight(holding) > 0.33,
                                    }"
                                    >{{ number(weight(holding), 'percent', 2) }}
                                </TableCell>
                                <TableCell class="text-right">{{ holding.yield }}%</TableCell>
                                <TableCell class="text-right">{{ price(yieldAmount(holding)) }}</TableCell>
                                <TableCell class="text-right">{{ holding.drp_weight }}%</TableCell>
                                <TableCell>{{ holding.dividend_frequency }}</TableCell>
                                <TableCell>{{ holding.registry }}</TableCell>
                                <TableCell>{{ holding.notes }}</TableCell>
                            </TableRow>

                            <TableRow class="font-bold">
                                <TableCell />
                                <TableCell class="text-right">{{ number(aggregates.units) }}</TableCell>
                                <TableCell />
                                <TableCell />
                                <TableCell class="text-right">{{ price(aggregates.cost) }}</TableCell>
                                <TableCell class="text-right">{{ price(aggregates.marketValue) }}</TableCell>
                                <TableCell class="text-right">{{ price(aggregates.profitLossAmount) }}</TableCell>
                                <TableCell class="text-right">{{ number(aggregates.profitLoss, 'percent', 2) }} </TableCell>
                                <TableCell class="text-right">100%</TableCell>
                                <TableCell class="text-right">{{ number(aggregates.yieldPercentage, 'percent', 2) }} </TableCell>
                                <TableCell class="text-right">{{ price(aggregates.yieldAmount) }}</TableCell>
                                <TableCell class="text-right">{{ number(aggregates.drpWeight, 'decimal', 2) }}% </TableCell>
                                <TableCell />
                                <TableCell />
                                <TableCell />
                            </TableRow>
                        </Deferred>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
