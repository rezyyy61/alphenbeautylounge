<script setup>
import { computed, onMounted, ref } from 'vue'
import { defineProps } from 'vue'
import { Line } from 'vue-chartjs'
import { isToday } from 'date-fns'

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    Filler,
    CategoryScale,
    LinearScale
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    Filler,
    CategoryScale,
    LinearScale
)

const props = defineProps({
    chartData: Object
})

const chartRef = ref(null)

const gradient = ref(null)

onMounted(() => {
    const chart = chartRef.value?.$el?.getContext('2d')
    if (chart) {
        const grad = chart.createLinearGradient(0, 0, 0, 200)
        grad.addColorStop(0, 'rgba(158, 131, 86, 0.4)')
        grad.addColorStop(1, 'rgba(255, 255, 255, 0)')
        gradient.value = grad
    }
})

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'index',
        intersect: false
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1,
                precision: 0
            },
            grid: {
                color: '#f0e9dc'
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    },
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: '#4a4137',
            titleFont: { weight: 'bold' },
            bodyColor: '#fff'
        }
    },
    elements: {
        point: {
            radius: 4,
            hoverRadius: 6,
            backgroundColor: '#9e8356'
        },
        line: {
            tension: 0.3
        }
    }
}

const chartWithGradient = computed(() => {
    if (!props.chartData) return null

    const rawLabels = props.chartData.rawLabels || props.chartData.labels

    const pointColors = rawLabels.map(date =>
        isToday(new Date(date)) ? '#e63946' : '#9e8356'
    )

    return {
        ...props.chartData,
        datasets: props.chartData.datasets.map(ds => ({
            ...ds,
            backgroundColor: gradient.value || 'rgba(158, 131, 86, 0.2)',
            fill: true,
            pointBackgroundColor: pointColors,
            pointBorderColor: pointColors,
        }))
    }
})

</script>

<template>
    <div class="w-full h-72">
        <Line ref="chartRef" :data="chartWithGradient" :options="chartOptions" />
    </div>
</template>
