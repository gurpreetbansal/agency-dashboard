var BASE_URL = $('.base_url').val();

var configGoalCompletionAllUsers = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletionAllUsersGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletionValueUsers = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletionValueUsersGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalConversionRate = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};
var configGoalConversionRateGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'top',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalAbondonRate = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalAbondonRateGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'top',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletionOrganic = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletionOrganicGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			// yAlign: 'top',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletionOverview = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			borderWidth:2
		}
		]
	},
	options: {
		elements: {
			point:{ 
				radius: 0,
				hoverRadius:5
			}
		},
		// responsive: true,
    	maintainAspectRatio: false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				offset:true
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				offset:true
			}]
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalValueOrganic = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalValueOrganicGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configConversionRateOrganic = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};
var configConversionRateOrganicGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			yAlign: 'center',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configAbondonRateOrganic  = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			// yAlign: 'top',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configAbondonRateOrganicGoals  = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}]
		},
		tooltips: {
			// yAlign: 'top',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            callbacks: {
            	label: function(tooltipItem, myData) {
            		var label = myData.datasets[tooltipItem.datasetIndex].label || '';
            		if (label) {
            			label += ': ';
            		}
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
		legend: {
			display:false
		}
	}
};

var configGoalCompletion = {
	type: 'line',
	data: {
		labels: [],
		datasets: [
		{
			label: "Goal Completions (All Users)",
			labels: [],
			fill: true,
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.05).rgbString(),
			borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},
		{
			label: "Goal Completions (Organic Traffic)",
			labels: [],
			fill: false,
			backgroundColor: color(window.chartColors.lightGreen).alpha(0.15).rgbString(),
			borderColor: color(window.chartColors.lightGreen).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		}
		]
	},
	options: {
		maintainAspectRatio: false,
		elements: {
			line: {
				tension: 0.000001
			}
			,
			point:{
				radius: 0,
				hitRadius	:1

			}
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			bodyFontStyle: 'bold',	
			callbacks: {
				labelTextColor: function(context) {
					return '#000';
				}
				,
				title: function() {}
				,
				beforeLabel: function(tooltipItem, data) {
					if(tooltipItem.datasetIndex === 0){
						return data.datasets[0].labels[tooltipItem.index];
					}
					else if(tooltipItem.datasetIndex === 2){
						return data.datasets[2].labels[tooltipItem.index];	
					}
				}
			}
		},
		legend: {
			labels: {
				boxWidth: 10
			}
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				ticks: {
					maxRotation: 0,
                    minRotation: 0,
					autoSkip: true,
					maxTicksLimit: 10
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Goal Completions'
				}
				, ticks: {
					min: 0,
				}
			}]
		}
	}
};

var configGoalCompletionGoals = {
	type: 'line',
	data: {
		labels: [],
		datasets: [
		{
			label: "Goal Completions (All Users)",
			labels: [],
			fill: true,
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.05).rgbString(),
			borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},
		{
			label: "Goal Completions (Organic Traffic)",
			labels: [],
			fill: false,
			backgroundColor: color(window.chartColors.lightGreen).alpha(0.15).rgbString(),
			borderColor: color(window.chartColors.lightGreen).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		}
		]
	},
	options: {
		maintainAspectRatio: false,
		elements: {
			line: {
				tension: 0.000001
			}
			,
			point:{
				radius: 0,
				hitRadius	:1

			}
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			bodyFontStyle: 'bold',	
			callbacks: {
				labelTextColor: function(context) {
					return '#000';
				}
				,
				title: function() {}
				,
				beforeLabel: function(tooltipItem, data) {
					if(tooltipItem.datasetIndex === 0){
						return data.datasets[0].labels[tooltipItem.index];
					}
					else if(tooltipItem.datasetIndex === 2){
						return data.datasets[2].labels[tooltipItem.index];	
					}
				}
			}
		},
		legend: {
			labels: {
				boxWidth: 10
			}
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: false,
					labelString: 'Month'
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				ticks: {
					maxRotation: 0,
					minRotation: 0,
					autoSkip: true,
					maxTicksLimit: 10
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Value'
				}
				, ticks: {
					min: 0,
				}
			}]
		}
	}
};

function goalCompletionChart(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_chart_data",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#goal_completion_add').css('display','block');
				$('#goal_completion_data').css('display','none');
				$('#analytics_data_goal').css('display','none');
			} 

			if(result['status'] == 1){
				goal_graph(result);
				$('#goal_completion_add').css('display','none');
				$('#goal_completion_data').css('display','block');
				$('#analytics_data_goal').css('display','block');
			}

				$('.goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}


function goal_graph(result){
	if (window.myLineGoalCompletion) {
		window.myLineGoalCompletion.destroy();
	}
	var ctxGoalCompletion = document.getElementById('canvas-goal-completion').getContext('2d');
	window.myLineGoalCompletion = new Chart(ctxGoalCompletion, configGoalCompletion);

	configGoalCompletion.data.labels =  result['from_datelabel'];
	configGoalCompletion.data.datasets[0].data = result['users'];
	configGoalCompletion.data.datasets[0].labels = result['from_datelabels'];
	configGoalCompletion.data.datasets[1].data = result['organic'];
	configGoalCompletion.data.datasets[1].labelString = result['from_datelabels'];

	if(result['compare_status'] == 1 || result['compare_status'] == '1'){
		configGoalCompletion.data.datasets.splice(2,2);
		configGoalCompletion.data.datasets.splice(3,3);

		var dataset_1 = {
			label: "Goal Completions (All Users)",
			fill: false,
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: result['previous_users'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		};
		var dataset_2 = {
			label: "Goal Completions (Organic Traffic)",
			fill: false,
			backgroundColor: window.chartColors.pink,
			borderColor: window.chartColors.pink,
			data: result['previous_organic'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		}

		configGoalCompletion.data.datasets.push(dataset_1);
		configGoalCompletion.data.datasets.push(dataset_2);

	} else{	
		configGoalCompletion.data.datasets.splice(2,2);
		configGoalCompletion.data.datasets.splice(3,3);

	}

	window.myLineGoalCompletion.update();
}

function goalCompletionChartGoals(campaign_id){

	console.log("side bar");
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_chart_data",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#goal_completion_add').css('display','block');
				$('#goal_completion_data').css('display','none');
				$('#analytics_data_goalmore').css('display','none');
				$('#goal_data_rank-view').css('display','block');
			} 

			if(result['status'] == 1){
				goal_graphView(result);
				$('#goal_completion_add').css('display','none');
				$('#goal_completion_data').css('display','block');
				$('#analytics_data_goalmore').css('display','block');
				$('#goal_data_rank-view').css('display','none');
			}

			$('.goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}

function goal_graphView(result){
	if (window.myLineGoalCompletionGaols) {
		window.myLineGoalCompletionGaols.destroy();
	}
	var ctxGoalCompletion = document.getElementById('canvas-goal-completion-goals').getContext('2d');
	window.myLineGoalCompletionGaols = new Chart(ctxGoalCompletion, configGoalCompletionGoals);

	configGoalCompletionGoals.data.labels =  result['from_datelabel'];
	configGoalCompletionGoals.data.datasets[0].data = result['users'];
	configGoalCompletionGoals.data.datasets[0].labels = result['from_datelabels'];
	configGoalCompletionGoals.data.datasets[1].data = result['organic'];
	configGoalCompletionGoals.data.datasets[1].labelString = result['from_datelabels'];

	if(result['compare_status'] == 1 || result['compare_status'] == '1'){
		configGoalCompletionGoals.data.datasets.splice(2,2);
		configGoalCompletionGoals.data.datasets.splice(3,3);

		var dataset_1 = {
			label: "Goal Completions (All Users)",
			fill: false,
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: result['previous_users'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		};
		var dataset_2 = {
			label: "Goal Completions (Organic Traffic)",
			fill: false,
			backgroundColor: window.chartColors.pink,
			borderColor: window.chartColors.pink,
			data: result['previous_organic'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		}

		configGoalCompletionGoals.data.datasets.push(dataset_1);
		configGoalCompletionGoals.data.datasets.push(dataset_2);

	} else{	
		configGoalCompletionGoals.data.datasets.splice(2,2);
		configGoalCompletionGoals.data.datasets.splice(3,3);

	}

	window.myLineGoalCompletionGaols.update();
}

function goalCompletionStats(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_overview",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			//overview field
			$('.goalToal').removeClass("ajax-loader");
			$('.goal').removeClass("ajax-loader");


			$('#goal-completion-users').html(result['current_goal_completion']);
			$('#goal-completion-traffic').html(result['current_goal_completion_organic']);
			$('#goal-value-users').html(result['current_goal_value']);
			$('#goal-value-organic').html(result['current_goal_value_organic']);
			$('#goal-conversion-rate-users').html(result['current_goal_conversion']+'%');
			$('#goal-conversion-rate-organic').html(result['current_goal_conversion_organic']+'%');
			$('#goal-abondon-rate-users').html(result['current_goal_abondon']+'%');
			$('#goal-abondon-rate-organic').html(result['current_goal_abondon_organic']+'%');
			if(result['compare_status'] == 1){
				$('.goals-chart-box').addClass('goals-compare-section');
				//goal completions
				$('#goal-completion-users').append(' <span><cite>vs</cite> '+result['previous_goal_completion']+ '</span>');
				if(result['goal_completion_percentage'] < 0){
					var string_goal_conpletion = result['goal_completion_percentage'].toString();
					var replace_goalCOmpletion = string_goal_conpletion.replace('-', '');
					var arrow = 'down'; var color = 'red';
				}else if(result['goal_completion_percentage'] > 0){
					var replace_goalCOmpletion = result['goal_completion_percentage'];
					var arrow = 'up'; var color = 'green';
				}else {
					var replace_goalCOmpletion = result['goal_completion_percentage'];
					var arrow = ''; var color = '';
				}
				$('.goal-completion-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+replace_goalCOmpletion+'%</cite>');

				//goal completions organic
				$('#goal-completion-traffic').append(' <span><cite>vs</cite> '+result['previous_goal_completion_organic']+ '</span>');
				if(result['goal_completion_percentage_organic'] < 0){
					var string_goal_conpletion_organic = result['goal_completion_percentage_organic'].toString();
					var replace_goalCOmpletion_organic = string_goal_conpletion_organic.replace('-', '');
					var arrow1 = 'down';
					var color1 = 'red';
				}else if(result['goal_completion_percentage_organic'] > 0){
					var replace_goalCOmpletion_organic = result['goal_completion_percentage_organic'];
					var arrow1 = 'up';
					var color1 = 'green';
				}else{
					var replace_goalCOmpletion_organic = result['goal_completion_percentage_organic'];
					var arrow1 = ''; var color1 = 'green';
				}
				$('.goal-completion-traffic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+ replace_goalCOmpletion_organic +'%</cite>');

				//goal value
				$('#goal-value-users').append(' <span><cite>vs</cite> '+result['previous_goal_value']+ '</span>');
				if(result['goal_value_percentage'] < 0){
					var string_goal_value = result['goal_value_percentage'].toString();
					var replace_goalValue = string_goal_value.replace('-', '');
					var arrow2 = 'down'; var color2 = 'red';
				}else if(result['goal_value_percentage'] > 0){
					var replace_goalValue = result['goal_value_percentage'];
					var arrow2 = 'up'; var color2 = 'green';
				}else{
					var replace_goalValue = result['goal_value_percentage'];
					var arrow2 = ''; var color2 = '';
				}
				$('.goal-value-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+ replace_goalValue +'%</cite>');

				//goal value organic
				$('#goal-value-organic').append(' <span><cite>vs</cite> '+result['previous_goal_value_organic']+ '</span>');
				if(result['goal_value_percentage_organic'] < 0){
					var string_goal_value_organic = result['goal_value_percentage_organic'].toString();
					var replace_goalValue_organic = string_goal_value_organic.replace('-', '');
					var arrow3 = 'down'; var color3 = 'red';
				}else if(result['goal_value_percentage_organic'] > 0){
					var replace_goalValue_organic = result['goal_value_percentage_organic'];
					var arrow3 = 'up'; var color3 = 'green';
				}else{
					var replace_goalValue_organic = result['goal_value_percentage_organic'];
					var arrow3 = color3 ='';
				}
				$('.goal-value-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+ replace_goalValue_organic +'%</cite>');
				
				//goal conversion rate users
				$('#goal-conversion-rate-users').append(' <span><cite>vs</cite> '+result['previous_goal_conversion']+ '%</span>');
				if(result['goal_conversion_rate_percentage'] < 0){
					var string_goal_conversionRate = result['goal_conversion_rate_percentage'].toString();
					var replace_goalConversionRate = string_goal_conversionRate.replace('-', '');
					var arrow4 = 'down'; var color4 = 'red';
				}else if(result['goal_conversion_rate_percentage'] > 0){
					var replace_goalConversionRate = result['goal_conversion_rate_percentage'];
					var arrow4 = 'up'; var color4 = 'green';
				}else{
					var replace_goalConversionRate = result['goal_conversion_rate_percentage'];
					var arrow4 = ''; var color4 = '';
				}
				$('.goal-conversion-rate-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+ replace_goalConversionRate +'%</cite>');

				//goal conversion rate organic
				$('#goal-conversion-rate-organic').append(' <span><cite>vs</cite> '+result['previous_goal_conversion_organic']+ '</span>');
				if(result['goal_conversion_rate_percentage_organic'] < 0){
					var string_goal_conversionRate_organic = result['goal_conversion_rate_percentage_organic'].toString();
					var replace_goalConversionRate_organic = string_goal_conversionRate_organic.replace('-', '');
					var arrow5 = 'down'; var color5 = 'red';
				}else if(result['goal_conversion_rate_percentage_organic'] > 0){
					var replace_goalConversionRate_organic = result['goal_conversion_rate_percentage_organic'];
					var arrow5 = 'up'; var color5 = 'green';
				}else{
					var replace_goalConversionRate_organic = result['goal_conversion_rate_percentage_organic'];
					var arrow5 = ''; var color5 = '';
				}
				$('.goal-conversion-rate-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+ replace_goalConversionRate_organic +'%</cite>');
				
				//goal Abandonment rate users
				$('#goal-abondon-rate-users').append(' <span><cite>vs</cite> '+result['previous_goal_abondon']+ '</span>');
				if(result['goal_abondon_rate_percentage'] < 0){
					var string_goal_abondon = result['goal_abondon_rate_percentage'].toString();
					var replace_goal_abondon = string_goal_abondon.replace('-', '');
					var arrow6 = 'down'; var color6 = 'red';
				}else if(result['goal_abondon_rate_percentage'] > 0){
					var replace_goal_abondon =  result['goal_abondon_rate_percentage'];
					var arrow6 = 'up'; var color6 = 'green';
				}else{
					var replace_goal_abondon =  result['goal_abondon_rate_percentage'];
					var arrow6 = ''; var color6 = '';
				}
				$('.goal-abondon-rate-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+ replace_goal_abondon +'%</cite>');

				//goal Abandonment rate organic
				$('#goal-abondon-rate-organic').append(' <span><cite>vs</cite> '+result['previous_goal_abondon_organic']+ '</span>');
				if(result['goal_abondon_rate_percentage_organic'] < 0){
					var string_goal_abondon_organic = result['goal_abondon_rate_percentage_organic'].toString();
					var replace_goal_abondon_organic = string_goal_abondon_organic.replace('-', '');
					var arrow7 = 'down'; var color7 = 'red';
				}else if(result['goal_abondon_rate_percentage_organic'] > 0){
					var replace_goal_abondon_organic = result['goal_abondon_rate_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}else{
					var replace_goal_abondon_organic = result['goal_abondon_rate_percentage_organic'];
					var arrow7 = ''; var color7 = '';
				}
				$('.goal-abondon-rate-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+ replace_goal_abondon_organic +'%</cite>');

			}else{
				$('.goals-chart-box').removeClass('goals-compare-section');
				$('.goal-completion-users-percentage').html('');
				$('.goal-completion-traffic-percentage').html('');
				$('.goal-value-users-percentage').html('');
				$('.goal-value-organic-percentage').html('');
				$('.goal-conversion-rate-users-percentage').html('');
				$('.goal-conversion-rate-organic-percentage').html('');
				$('.goal-abondon-rate-users-percentage').html('');
				$('.goal-abondon-rate-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.goal_completion_percentage').removeClass('ajax-loader');

		}
	});
}


function goalCompletionStatsGoals(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_overview",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			//overview field
			$('.Google-analytics-goal').text(result['current_goal_completion_organic']);
			$('.goal_result').html('<span uk-icon="icon: arrow-up"></span>'+result['goal_completion_percentage_organic']);
			if(result['goal_completion_percentage_organic'] > 0 ){
					$('.google-analytics-goal-foot').find('span').attr("uk-icon",'icon: arrow-up');
					$('.google-analytics-goal-foot').find('cite').removeClass("red");
					$('.google-analytics-goal-foot').find('cite').addClass("green");
				}else if(result['goal_completion_percentage_organic'] < 0 ){
					$('.google-analytics-goal-foot').find('span').attr("uk-icon",'icon: arrow-down');
					$('.google-analytics-goal-foot').find('cite').removeClass("green");
					$('.google-analytics-goal-foot').find('cite').addClass("red");
				}else{
					$('.google-analytics-goal-foot').find('span').removeAttr("uk-icon");
				}
				$('.goalToal').removeClass("ajax-loader");
				$('.goal').removeClass("ajax-loader");


			$('#goal-completion-usersGoals').html(result['current_goal_completion']);
			$('#goal-completion-trafficGoals').html(result['current_goal_completion_organic']);
			$('#goal-value-usersGoals').html(result['current_goal_value']);
			$('#goal-value-organicGoals').html(result['current_goal_value_organic']);
			$('#goal-conversion-rate-usersGoals').html(result['current_goal_conversion']+'%');
			$('#goal-conversion-rate-organicGoals').html(result['current_goal_conversion_organic']+'%');
			$('#goal-abondon-rate-usersGoals').html(result['current_goal_abondon']+'%');
			$('#goal-abondon-rate-organicGoals').html(result['current_goal_abondon_organic']+'%');
			if(result['compare_status'] == 1){
				$('.goals-chart-box').addClass('goals-compare-section');	
				//goal completions
				$('#goal-completion-users').append(' <span><cite>vs</cite> '+result['previous_goal_completion']+ '</span>');
				if(result['goal_completion_percentage'] < 0){
					var arrow = 'down';
					var color = 'red';
				}else{
					var arrow = 'up';
					var color = 'green';
				}
				$('.goal-completion-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+result['goal_completion_percentage']+'%</cite>');

				//goal completions organic
				$('#goal-completion-traffic').append(' <span><cite>vs</cite> '+result['previous_goal_completion_organic']+ '</span>');
				if(result['goal_completion_percentage_organic'] < 0){
					var arrow1 = 'down';
					var color1 = 'red';
				}else{
					var arrow1 = 'up';
					var color1 = 'green';
				}
				$('.goal-completion-traffic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+result['goal_completion_percentage_organic']+'%</cite>');

				//goal value
				$('#goal-value-users').append(' <span><cite>vs</cite> '+result['previous_goal_value']+ '</span>');
				if(result['goal_value_percentage'] < 0){
					var arrow2 = 'down'; var color2 = 'red';
				}else{
					var arrow2 = 'up'; var color2 = 'green';
				}
				$('.goal-value-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+result['goal_value_percentage']+'%</cite>');

				//goal value organic
				$('#goal-value-organic').append(' <span><cite>vs</cite> '+result['previous_goal_value_organic']+ '</span>');
				if(result['goal_value_percentage_organic'] < 0){
					var arrow3 = 'down'; var color3 = 'red';
				}else{
					var arrow3 = 'up'; var color3 = 'green';
				}
				$('.goal-value-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+result['goal_value_percentage_organic']+'%</cite>');
				
				//goal conversion rate users
				$('#goal-conversion-rate-users').append(' <span><cite>vs</cite> '+result['previous_goal_conversion']+ '%</span>');
				if(result['goal_conversion_rate_percentage'] < 0){
					var arrow4 = 'down'; var color4 = 'red';
				}else{
					var arrow4 = 'up'; var color4 = 'green';
				}
				$('.goal-conversion-rate-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+result['goal_conversion_rate_percentage']+'%</cite>');

				//goal conversion rate organic
				$('#goal-conversion-rate-organic').append(' <span><cite>vs</cite> '+result['previous_goal_conversion_organic']+ '</span>');
				if(result['goal_conversion_rate_percentage_organic'] < 0){
					var arrow5 = 'down'; var color5 = 'red';
				}else{
					var arrow5 = 'up'; var color5 = 'green';
				}
				$('.goal-conversion-rate-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+result['goal_conversion_rate_percentage_organic']+'%</cite>');
				
				//goal Abandonment rate users
				$('#goal-abondon-rate-users').append(' <span><cite>vs</cite> '+result['previous_goal_abondon']+ '</span>');
				if(result['goal_abondon_rate_percentage'] < 0){
					var arrow6 = 'down'; var color6 = 'red';
				}else{
					var arrow6 = 'up'; var color6 = 'green';
				}
				$('.goal-abondon-rate-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+result['goal_abondon_rate_percentage']+'%</cite>');

				//goal Abandonment rate organic
				$('#goal-abondon-rate-organic').append(' <span><cite>vs</cite> '+result['previous_goal_abondon_organic']+ '</span>');
				if(result['goal_abondon_rate_percentage_organic'] < 0){
					var arrow7 = 'down'; var color7 = 'red';
				}else{
					var arrow7 = 'up'; var color7 = 'green';
				}
				$('.goal-abondon-rate-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+result['goal_abondon_rate_percentage_organic']+'%</cite>');

			}else{
				$('.goals-chart-box').removeClass('goals-compare-section');	
				$('.goal-completion-users-percentage').html('');
				$('.goal-completion-traffic-percentage').html('');
				$('.goal-value-users-percentage').html('');
				$('.goal-value-organic-percentage').html('');
				$('.goal-conversion-rate-users-percentage').html('');
				$('.goal-conversion-rate-organic-percentage').html('');
				$('.goal-abondon-rate-users-percentage').html('');
				$('.goal-abondon-rate-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.goal_completion_percentage').removeClass('ajax-loader');

		}
	});
}


/*$(document).ready(function(){
	goal_completion_location($('.campaign_id').val(),1);
	goal_completion_sourcemedium($('.campaign_id').val(),1);
	all_users_chart($('.campaign_id').val());
	goal_value_chart($('.campaign_id').val());
	goal_conversion_rate_chart($('.campaign_id').val());
	goal_abondon_rate_chart($('.campaign_id').val());
	goal_completion_chart_organic($('.campaign_id').val());
	goal_value_chart_organic($('.campaign_id').val());
	goal_conversionRate_chart_organic($('.campaign_id').val());
	goal_abondonRate_chart_organic($('.campaign_id').val());
});*/

function goal_completion_location(campaign_id,page){
	$('#goal_completion_location tr td').addClass('ajax-loader');
	$('.GoalComp-Location').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		data:{campaign_id,page},
		url:BASE_URL +'/ajax_get_goal_completion_location',
		success:function(response){
			$('#goal_completion_location tbody').html(response);
			$('#goal_completion_location tr').removeClass('ajax-loader');
			$('#goal_completion_location tr td').removeClass('ajax-loader');
		}
	});

	$.ajax({
		type:'GET',
		data:{campaign_id,page},
		url:BASE_URL +'/ajax_get_goal_completion_location_pagination',
		success:function(response){
				$('.goalCompletion-location-foot').html('');
			    $('.goalCompletion-location-foot').html(response);
			    $('.GoalComp-Location').removeClass('ajax-loader');
			
		}
	});
}

function goal_completion_sourcemedium(campaign_id,page){
	$('#goal_completion_sourcemedium tr td').addClass('ajax-loader');
	$('.GoalComp-sourcemedium').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		data:{campaign_id,page},
		url:BASE_URL +'/ajax_get_goal_completion_sourcemedium',
		success:function(response){
			$('#goal_completion_sourcemedium tbody').html(response);
			$('#goal_completion_sourcemedium tr').removeClass('ajax-loader');
			$('#goal_completion_sourcemedium tr td').removeClass('ajax-loader');
			
		}
	});

	$.ajax({
		type:'GET',
		data:{campaign_id,page},
		url:BASE_URL +'/ajax_get_goal_completion_sourcemedium_pagination',
		success:function(response){
			$('.goalCompletion-sourceMedium-foot').html('');
		    $('.goalCompletion-sourceMedium-foot').html(response);
		    $('.GoalComp-sourcemedium').removeClass('ajax-loader');
		}
	});
}


function all_users_chart(campaign_id,value,state){	
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_completion_all_users_chart",
		data: {campaign_id,value,state},	
		dataType: 'json',
		success: function(result) {
			// if(window.myGoalCompletionUser){
			// 	window.myGoalCompletionUser.destroy();
			// }

			var ctx = document.getElementById('goal-completion-all-users-new').getContext('2d');
			window.myGoalCompletionUser = new Chart(ctx, configGoalCompletionAllUsers);


			configGoalCompletionAllUsers.data.labels = result['from_datelabel'];
			configGoalCompletionAllUsers.data.datasets[0].data = result['data'];

			window.myGoalCompletionUser.update();

			$('.allUserGraph').removeClass('ajax-loader');
			$('.allUserGraph').hide();
		}
	});
}

function all_users_chartGoals(campaign_id,value,state){	
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_completion_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalCompletionUserGoals){
				window.myGoalCompletionUserGoals.destroy();
			}

			var ctx = document.getElementById('goal-completion-all-usersGoals').getContext('2d');
			window.myGoalCompletionUserGoals = new Chart(ctx, configGoalCompletionAllUsersGoals);


			configGoalCompletionAllUsersGoals.data.labels = result['from_datelabel'];
			configGoalCompletionAllUsersGoals.data.datasets[0].data = result['data'];

			window.myGoalCompletionUserGoals.update();

			$('.allUserGraph').removeClass('ajax-loader');
			$('.allUserGraph').hide();
		}
	});
}


function goal_value_chart(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_value_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalCompletionValue){
				window.myGoalCompletionValue.destroy();
			}

			var ctx = document.getElementById('goal-value-all-users-new').getContext('2d');
			window.myGoalCompletionValue = new Chart(ctx, configGoalCompletionValueUsers);


			configGoalCompletionValueUsers.data.labels = result['from_datelabel'];
			configGoalCompletionValueUsers.data.datasets[0].data = result['data'];

			window.myGoalCompletionValue.update();

			$('.goalValueGraph').removeClass('ajax-loader');
			$('.goalValueGraph').hide();
		}
	});
}

function goal_value_chartGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_value_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalCompletionValueGoals){
				window.myGoalCompletionValueGoals.destroy();
			}

			var ctx = document.getElementById('goal-value-all-usersGoals').getContext('2d');
			window.myGoalCompletionValueGoals = new Chart(ctx, configGoalCompletionValueUsersGoals);


			configGoalCompletionValueUsersGoals.data.labels = result['from_datelabel'];
			configGoalCompletionValueUsersGoals.data.datasets[0].data = result['data'];

			window.myGoalCompletionValueGoals.update();

			$('.goalValueGraph').removeClass('ajax-loader');
			$('.goalValueGraph').hide();
		}
	});
} 

function goal_conversion_rate_chart(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_conversion_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalConversionRate){
				window.myGoalConversionRate.destroy();
			}

			var ctx = document.getElementById('goal-conversion-all-users-new').getContext('2d');
			window.myGoalConversionRate = new Chart(ctx, configGoalConversionRate);


			configGoalConversionRate.data.labels = result['from_datelabel'];
			configGoalConversionRate.data.datasets[0].data = result['data'];

			window.myGoalConversionRate.update();

			$('.goalConversionRateGraph').removeClass('ajax-loader');
			$('.goalConversionRateGraph').hide();
		}
	});
}

function goal_conversion_rate_chartGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_conversion_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalConversionRateGoals){
				window.myGoalConversionRateGoals.destroy();
			}

			var ctx = document.getElementById('goal-conversion-all-usersGoals').getContext('2d');
			window.myGoalConversionRateGoals = new Chart(ctx, configGoalConversionRateGoals);


			configGoalConversionRateGoals.data.labels = result['from_datelabel'];
			configGoalConversionRateGoals.data.datasets[0].data = result['data'];

			window.myGoalConversionRateGoals.update();

			$('.goalConversionRateGraph').removeClass('ajax-loader');
			$('.goalConversionRateGraph').hide();
		}
	});
}

function goal_abondon_rate_chart(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_abondon_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalAbondonRate){
				window.myGoalAbondonRate.destroy();
			}

			var ctx = document.getElementById('goal-abondon-all-users-new').getContext('2d');
			window.myGoalAbondonRate = new Chart(ctx, configGoalAbondonRate);


			configGoalAbondonRate.data.labels = result['from_datelabel'];
			configGoalAbondonRate.data.datasets[0].data = result['data'];

			window.myGoalAbondonRate.update();

			$('.goalAbandonRateGraph').removeClass('ajax-loader');
			$('.goalAbandonRateGraph').hide();
		}
	});
}

function goal_abondon_rate_chartGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_abondon_all_users_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalAbondonRateGoals){
				window.myGoalAbondonRateGoals.destroy();
			}

			var ctx = document.getElementById('goal-abondon-all-usersGoals').getContext('2d');
			window.myGoalAbondonRateGoals = new Chart(ctx, configGoalAbondonRateGoals);


			configGoalAbondonRateGoals.data.labels = result['from_datelabel'];
			configGoalAbondonRateGoals.data.datasets[0].data = result['data'];

			window.myGoalAbondonRateGoals.update();

			$('.goalAbandonRateGraph').removeClass('ajax-loader');
			$('.goalAbandonRateGraph').hide();
		}
	});
} 


function goal_completion_chart_organic(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_completion_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			//goal-completion-section
			if(window.myGoalCompletionOrganic){
				window.myGoalCompletionOrganic.destroy();
			}

			var ctx = document.getElementById('goal-completion-organic-new').getContext('2d');
			window.myGoalCompletionOrganic = new Chart(ctx, configGoalCompletionOrganic);


			configGoalCompletionOrganic.data.labels = result['from_datelabel'];
			configGoalCompletionOrganic.data.datasets[0].data = result['data'];

			window.myGoalCompletionOrganic.update();

			$('.OrganicGraph').removeClass('ajax-loader');
			$('.OrganicGraph').hide();
		}
	});
}

function goal_completion_chart_organicGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_completion_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			//goal-completion-section
			if(window.myGoalCompletionOrganicGoals){
				window.myGoalCompletionOrganicGoals.destroy();
			}

			var ctx = document.getElementById('goal-completion-organicGoals').getContext('2d');
			window.myGoalCompletionOrganicGoals = new Chart(ctx, configGoalCompletionOrganicGoals);


			configGoalCompletionOrganicGoals.data.labels = result['from_datelabel'];
			configGoalCompletionOrganicGoals.data.datasets[0].data = result['data'];

			window.myGoalCompletionOrganicGoals.update();

			$('.OrganicGraph').removeClass('ajax-loader');
			$('.OrganicGraph').hide();
		}
	});
}

function goal_value_chart_organic(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_value_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalValueOrganic){
				window.myGoalValueOrganic.destroy();
			}

			var ctx = document.getElementById('goal-value-organic-chart-new').getContext('2d');
			window.myGoalValueOrganic = new Chart(ctx, configGoalValueOrganic);


			configGoalValueOrganic.data.labels = result['from_datelabel'];
			configGoalValueOrganic.data.datasets[0].data = result['data'];

			window.myGoalValueOrganic.update();

			$('.ValueOrganicGraph').removeClass('ajax-loader');
			$('.ValueOrganicGraph').hide();
		}
	});
}

function goal_value_chart_organicGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_value_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalValueOrganicGoals){
				window.myGoalValueOrganicGoals.destroy();
			}

			var ctx = document.getElementById('goal-value-organic-chartGoals').getContext('2d');
			window.myGoalValueOrganicGoals = new Chart(ctx, configGoalValueOrganicGoals);


			configGoalValueOrganicGoals.data.labels = result['from_datelabel'];
			configGoalValueOrganicGoals.data.datasets[0].data = result['data'];

			window.myGoalValueOrganicGoals.update();

			$('.ValueOrganicGraph').removeClass('ajax-loader');
			$('.ValueOrganicGraph').hide();
		}
	});
}

function goal_conversionRate_chart_organic(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_conversion_rate_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myConversionRateOrganic){
				window.myConversionRateOrganic.destroy();
			}

			var ctx = document.getElementById('goal-conversionRate-organic-chart-new').getContext('2d');
			window.myConversionRateOrganic = new Chart(ctx, configConversionRateOrganic);


			configConversionRateOrganic.data.labels = result['from_datelabel'];
			configConversionRateOrganic.data.datasets[0].data = result['data'];

			window.myConversionRateOrganic.update();

			$('.ConversionRateOrganicGraph').removeClass('ajax-loader');
			$('.ConversionRateOrganicGraph').hide();
		}
	});
}

function goal_conversionRate_chart_organicGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_conversion_rate_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myConversionRateOrganicGoals){
				window.myConversionRateOrganicGoals.destroy();
			}

			var ctx = document.getElementById('goal-conversionRate-organic-chartGoals').getContext('2d');
			window.myConversionRateOrganicGoals = new Chart(ctx, configConversionRateOrganicGoals);


			configConversionRateOrganicGoals.data.labels = result['from_datelabel'];
			configConversionRateOrganicGoals.data.datasets[0].data = result['data'];

			window.myConversionRateOrganicGoals.update();

			$('.ConversionRateOrganicGraph').removeClass('ajax-loader');
			$('.ConversionRateOrganicGraph').hide();
		}
	});
}

function goal_abondonRate_chart_organic(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_abondon_rate_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myabondonRateOrganic){
				window.myabondonRateOrganic.destroy();
			}

			var ctx = document.getElementById('goal-abondonRate-organic-chart-new').getContext('2d');
			window.myabondonRateOrganic = new Chart(ctx, configAbondonRateOrganic);


			configAbondonRateOrganic.data.labels = result['from_datelabel'];
			configAbondonRateOrganic.data.datasets[0].data = result['data'];

			window.myabondonRateOrganic.update();

			$('.AbondonRateOrganicGraph').removeClass('ajax-loader');
			$('.AbondonRateOrganicGraph').hide();
		}
	});
}
function goal_abondonRate_chart_organicGoals(campaign_id,value,state){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_abondon_rate_organic_chart",
		data: {campaign_id,value,state},
		dataType: 'json',
		success: function(result) {
			if(window.myabondonRateOrganicGoals){
				window.myabondonRateOrganicGoals.destroy();
			}

			var ctx = document.getElementById('goal-abondonRate-organic-chartGoals').getContext('2d');
			window.myabondonRateOrganicGoals = new Chart(ctx, configAbondonRateOrganicGoals);


			configAbondonRateOrganicGoals.data.labels = result['from_datelabel'];
			configAbondonRateOrganicGoals.data.datasets[0].data = result['data'];

			window.myabondonRateOrganicGoals.update();

			$('.AbondonRateOrganicGraph').removeClass('ajax-loader');
			$('.AbondonRateOrganicGraph').hide();
		}
	});
}

$(document).on('click','.GoalComp-Location a',function(e){
	e.preventDefault();

	$('GoalComp-Location ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	goal_completion_location($('.campaign_id').val(),page);
});

$(document).on('click','.GoalComp-sourcemedium a',function(e){
	e.preventDefault();

	$('GoalComp-sourcemedium ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	goal_completion_sourcemedium($('.campaign_id').val(),page);
});


function ifGoalExists(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_check_goal_completion_count',
		data:{campaign_id},
		dataType:'json',
		success:function(response){
			console.log(response['status']);
			return response['status'];
		}
	})
}


function goal_completion_chart_overview(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_goal_completion_organic_chart_overview",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(window.myGoalCompletionOverview){
				window.myGoalCompletionOverview.destroy();
			}

			var ctx_overview = document.getElementById('google-goal-completion-overview').getContext('2d');
			window.myGoalCompletionOverview = new Chart(ctx_overview, configGoalCompletionOverview);
			var gradient = gradientColor(ctx_overview);
			configGoalCompletionOverview.data.labels = result['from_datelabel'];
			configGoalCompletionOverview.data.datasets[0].data = result['data'];
			configGoalCompletionOverview.data.datasets[0].backgroundColor = gradient;

			window.myGoalCompletionOverview.update();
			$('.gc-overview-organic').removeClass('ajax-loader');
		}
	});
}



function goal_completion_stats_overview(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_stats_overview",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['current_goal_completion_organic'] != '??'){
					var ga_string = result['goal_completion_percentage_organic'].toString();
					ga_string = ga_string.replace(/,/g, "");
				if(ga_string > 0 ){
					$('.Google-analytics-goal').html(result['current_goal_completion_organic']);
					$('.goal_result').addClass("green");
					$('.goal_result').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['goal_completion_percentage_organic']+'% </span>Since Start');
				}else if(ga_string < 0 ){
					var replace_ga = ga_string.replace('-', '');
					$('.Google-analytics-goal').html(result['current_goal_completion_organic']);
					$('.goal_result').addClass("red");
					$('.goal_result').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_ga+'% </span>Since Start');
				}else{
					$('.Google-analytics-goal').html(result['current_goal_completion_organic']);
				}
			}else{
				$('.Google-analytics-goal').html(result['current_goal_completion_organic']);
			}

			$('.goalToal').removeClass("ajax-loader");
			$('.goal').removeClass("ajax-loader");		
		}
	});
}

function goalCompletionChart_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_chart_data_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#goal_completion_add').css('display','block');
				$('#goal_completion_data').css('display','none');
				$('#analytics_data_goal').css('display','none');
			}else{
				goal_graph(result);
				$('#goal_completion_add').css('display','none');
				$('#goal_completion_data').css('display','block');
				$('#analytics_data_goal').css('display','block');
			}

			$('.goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}


function goalCompletionStats_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_overview_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			$('.goalToal').removeClass("ajax-loader");
			$('.goal').removeClass("ajax-loader");


			$('#goal-completion-users').html(result['current_goal_completion']);
			$('#goal-completion-traffic').html(result['current_goal_completion_organic']);
			$('#goal-value-users').html(result['current_goal_value']);
			$('#goal-value-organic').html(result['current_goal_value_organic']);
			$('#goal-conversion-rate-users').html(result['current_goal_conversion']+'%');
			$('#goal-conversion-rate-organic').html(result['current_goal_conversion_organic']+'%');
			$('#goal-abondon-rate-users').html(result['current_goal_abondon']+'%');
			$('#goal-abondon-rate-organic').html(result['current_goal_abondon_organic']+'%');
			if(result['compare_status'] == 1){
				$('.goals-chart-box').addClass('goals-compare-section');
				//goal completions
				$('#goal-completion-users').append('<span><cite>vs</cite> '+result['previous_goal_completion']+ '</span>');
				if(result['goal_completion_percentage'] < 0){
					var string_goal_conpletion = result['goal_completion_percentage'].toString();
					var replace_goalCOmpletion = string_goal_conpletion.replace('-', '');
					var arrow = 'down'; var color = 'red';
				}else if(result['goal_completion_percentage'] > 0){
					var replace_goalCOmpletion = result['goal_completion_percentage'];
					var arrow = 'up'; var color = 'green';
				}else {
					var replace_goalCOmpletion = result['goal_completion_percentage'];
					var arrow = ''; var color = '';
				}
				$('.goal-completion-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+replace_goalCOmpletion+'%</cite>');

				//goal completions organic
				$('#goal-completion-traffic').append('<span><cite>vs</cite> '+result['previous_goal_completion_organic']+ '</span>');
				if(result['goal_completion_percentage_organic'] < 0){
					var string_goal_conpletion_organic = result['goal_completion_percentage_organic'].toString();
					var replace_goalCOmpletion_organic = string_goal_conpletion_organic.replace('-', '');
					var arrow1 = 'down';
					var color1 = 'red';
				}else if(result['goal_completion_percentage_organic'] > 0){
					var replace_goalCOmpletion_organic = result['goal_completion_percentage_organic'];
					var arrow1 = 'up';
					var color1 = 'green';
				}else{
					var replace_goalCOmpletion_organic = result['goal_completion_percentage_organic'];
					var arrow1 = ''; var color1 = 'green';
				}
				$('.goal-completion-traffic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+ replace_goalCOmpletion_organic +'%</cite>');

				//goal value
				$('#goal-value-users').append('<span><cite>vs</cite> '+result['previous_goal_value']+ '</span>');
				if(result['goal_value_percentage'] < 0){
					var string_goal_value = result['goal_value_percentage'].toString();
					var replace_goalValue = string_goal_value.replace('-', '');
					var arrow2 = 'down'; var color2 = 'red';
				}else if(result['goal_value_percentage'] > 0){
					var replace_goalValue = result['goal_value_percentage'];
					var arrow2 = 'up'; var color2 = 'green';
				}else{
					var replace_goalValue = result['goal_value_percentage'];
					var arrow2 = ''; var color2 = '';
				}
				$('.goal-value-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+ replace_goalValue +'%</cite>');

				//goal value organic
				$('#goal-value-organic').append('<span><cite>vs</cite> '+result['previous_goal_value_organic']+ '</span>');
				if(result['goal_value_percentage_organic'] < 0){
					var string_goal_value_organic = result['goal_value_percentage_organic'].toString();
					var replace_goalValue_organic = string_goal_value_organic.replace('-', '');
					var arrow3 = 'down'; var color3 = 'red';
				}else if(result['goal_value_percentage_organic'] > 0){
					var replace_goalValue_organic = result['goal_value_percentage_organic'];
					var arrow3 = 'up'; var color3 = 'green';
				}else{
					var replace_goalValue_organic = result['goal_value_percentage_organic'];
					var arrow3 = color3 ='';
				}
				$('.goal-value-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+ replace_goalValue_organic +'%</cite>');
				
				//goal conversion rate users
				$('#goal-conversion-rate-users').append('<span><cite>vs</cite> '+result['previous_goal_conversion']+ '%</span>');
				if(result['goal_conversion_rate_percentage'] < 0){
					var string_goal_conversionRate = result['goal_conversion_rate_percentage'].toString();
					var replace_goalConversionRate = string_goal_conversionRate.replace('-', '');
					var arrow4 = 'down'; var color4 = 'red';
				}else if(result['goal_conversion_rate_percentage'] > 0){
					var replace_goalConversionRate = result['goal_conversion_rate_percentage'];
					var arrow4 = 'up'; var color4 = 'green';
				}else{
					var replace_goalConversionRate = result['goal_conversion_rate_percentage'];
					var arrow4 = ''; var color4 = '';
				}
				$('.goal-conversion-rate-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+ replace_goalConversionRate +'%</cite>');

				//goal conversion rate organic
				$('#goal-conversion-rate-organic').append('<span><cite>vs</cite> '+result['previous_goal_conversion_organic']+ '</span>');
				if(result['goal_conversion_rate_percentage_organic'] < 0){
					var string_goal_conversionRate_organic = result['goal_conversion_rate_percentage_organic'].toString();
					var replace_goalConversionRate_organic = string_goal_conversionRate_organic.replace('-', '');
					var arrow5 = 'down'; var color5 = 'red';
				}else if(result['goal_conversion_rate_percentage_organic'] > 0){
					var replace_goalConversionRate_organic = result['goal_conversion_rate_percentage_organic'];
					var arrow5 = 'up'; var color5 = 'green';
				}else{
					var replace_goalConversionRate_organic = result['goal_conversion_rate_percentage_organic'];
					var arrow5 = ''; var color5 = '';
				}
				$('.goal-conversion-rate-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+ replace_goalConversionRate_organic +'%</cite>');
				
				//goal Abandonment rate users
				$('#goal-abondon-rate-users').append('<span><cite>vs</cite> '+result['previous_goal_abondon']+ '</span>');
				if(result['goal_abondon_rate_percentage'] < 0){
					var string_goal_abondon = result['goal_abondon_rate_percentage'].toString();
					var replace_goal_abondon = string_goal_abondon.replace('-', '');
					var arrow6 = 'down'; var color6 = 'red';
				}else if(result['goal_abondon_rate_percentage'] > 0){
					var replace_goal_abondon =  result['goal_abondon_rate_percentage'];
					var arrow6 = 'up'; var color6 = 'green';
				}else{
					var replace_goal_abondon =  result['goal_abondon_rate_percentage'];
					var arrow6 = ''; var color6 = '';
				}
				$('.goal-abondon-rate-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+ replace_goal_abondon +'%</cite>');

				//goal Abandonment rate organic
				$('#goal-abondon-rate-organic').append('<span><cite>vs</cite> '+result['previous_goal_abondon_organic']+ '</span>');
				if(result['goal_abondon_rate_percentage_organic'] < 0){
					var string_goal_abondon_organic = result['goal_abondon_rate_percentage_organic'].toString();
					var replace_goal_abondon_organic = string_goal_abondon_organic.replace('-', '');
					var arrow7 = 'down'; var color7 = 'red';
				}else if(result['goal_abondon_rate_percentage_organic'] > 0){
					var replace_goal_abondon_organic = result['goal_abondon_rate_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}else{
					var replace_goal_abondon_organic = result['goal_abondon_rate_percentage_organic'];
					var arrow7 = ''; var color7 = '';
				}
				$('.goal-abondon-rate-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+ replace_goal_abondon_organic +'%</cite>');

			}else{
				$('.goals-chart-box').removeClass('goals-compare-section');
				$('.goal-completion-users-percentage').html('');
				$('.goal-completion-traffic-percentage').html('');
				$('.goal-value-users-percentage').html('');
				$('.goal-value-organic-percentage').html('');
				$('.goal-conversion-rate-users-percentage').html('');
				$('.goal-conversion-rate-organic-percentage').html('');
				$('.goal-abondon-rate-users-percentage').html('');
				$('.goal-abondon-rate-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.goal_completion_percentage').removeClass('ajax-loader');

		}
	});
}


function goal_completion_location_vk(value,compare_value,campaignId,key,type,page){
	$('#goal_completion_location tr td').addClass('ajax-loader');
	$('.GoalComp-Location').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		data:{value,compare_value,campaign_id:campaignId,key,type,page},
		url:BASE_URL +'/ajax_goal_completion_location_vk',
		success:function(response){
			$('#goal_completion_location tbody').html(response);
			$('#goal_completion_location tr').removeClass('ajax-loader');
			$('#goal_completion_location tr td').removeClass('ajax-loader');
		}
	});

	$.ajax({
		type:'GET',
		data:{value,compare_value,campaign_id:campaignId,key,type,page},
		url:BASE_URL +'/ajax_goal_completion_location_pagination_vk',
		success:function(response){
			$('.goalCompletion-location-foot').html('');
			$('.goalCompletion-location-foot').html(response);
			$('.GoalComp-Location').removeClass('ajax-loader');
			
		}
	});
}

function goal_completion_sourcemedium_vk(value,compare_value,campaignId,key,type,page){
	$('#goal_completion_sourcemedium tr td').addClass('ajax-loader');
	$('.GoalComp-sourcemedium').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		data:{value,compare_value,campaign_id:campaignId,key,type,page},
		url:BASE_URL +'/ajax_goal_completion_sourcemedium_vk',
		success:function(response){
			$('#goal_completion_sourcemedium tbody').html(response);
			$('#goal_completion_sourcemedium tr').removeClass('ajax-loader');
			$('#goal_completion_sourcemedium tr td').removeClass('ajax-loader');
			
		}
	});

	$.ajax({
		type:'GET',
		data:{value,compare_value,campaign_id:campaignId,key,type,page},
		url:BASE_URL +'/ajax_goal_completion_sourcemedium_pagination_vk',
		success:function(response){
			$('.goalCompletion-sourceMedium-foot').html('');
			$('.goalCompletion-sourceMedium-foot').html(response);
			$('.GoalComp-sourcemedium').removeClass('ajax-loader');
		}
	});
}


function goalCompletionChartGoals_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_chart_data_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#goal_completion_add').css('display','block');
				$('#goal_completion_data').css('display','none');
				$('#analytics_data_goalmore').css('display','none');
				$('#goal_data_rank-view').css('display','block');
			}else{
				goal_graphView(result);
				$('#goal_completion_add').css('display','none');
				$('#goal_completion_data').css('display','block');
				$('#analytics_data_goalmore').css('display','block');
				$('#goal_data_rank-view').css('display','none');
			}

			$('.goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}

function goalCompletionStatsGoals_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_goal_completion_overview_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			//overview field
			$('.Google-analytics-goal').text(result['current_goal_completion_organic']);
			$('.goal_result').html('<span uk-icon="icon: arrow-up"></span>'+result['goal_completion_percentage_organic']);
			if(result['goal_completion_percentage_organic'] > 0 ){
				$('.google-analytics-goal-foot').find('span').attr("uk-icon",'icon: arrow-up');
				$('.google-analytics-goal-foot').find('cite').addClass("green");
			}else if(result['goal_completion_percentage_organic'] < 0 ){
				$('.google-analytics-goal-foot').find('span').attr("uk-icon",'icon: arrow-down');
				$('.google-analytics-goal-foot').find('cite').addClass("red");
			}else{
				$('.google-analytics-goal-foot').find('span').removeAttr("uk-icon");
			}
			$('.goalToal').removeClass("ajax-loader");
			$('.goal').removeClass("ajax-loader");


			$('#goal-completion-usersGoals').html(result['current_goal_completion']);
			$('#goal-completion-trafficGoals').html(result['current_goal_completion_organic']);
			$('#goal-value-usersGoals').html(result['current_goal_value']);
			$('#goal-value-organicGoals').html(result['current_goal_value_organic']);
			$('#goal-conversion-rate-usersGoals').html(result['current_goal_conversion']+'%');
			$('#goal-conversion-rate-organicGoals').html(result['current_goal_conversion_organic']+'%');
			$('#goal-abondon-rate-usersGoals').html(result['current_goal_abondon']+'%');
			$('#goal-abondon-rate-organicGoals').html(result['current_goal_abondon_organic']+'%');
			if(result['compare_status'] == '1'){
				$('.goals-chart-box').addClass('goals-compare-section');
				//goal completions
				$('#goal-completion-usersGoals').append('<span><cite>vs</cite> '+result['previous_goal_completion']+ '</span>');
				if(result['goal_completion_percentage'] < 0){
					var arrow = 'down';
					var color = 'red';
				}else{
					var arrow = 'up';
					var color = 'green';
				}
				$('.goal-completion-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+result['goal_completion_percentage']+'%</cite>');

				//goal completions organic
				$('#goal-completion-trafficGoals').append('<span><cite>vs</cite> '+result['previous_goal_completion_organic']+ '</span>');
				if(result['goal_completion_percentage_organic'] < 0){
					var arrow1 = 'down';
					var color1 = 'red';
				}else{
					var arrow1 = 'up';
					var color1 = 'green';
				}
				$('.goal-completion-traffic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+result['goal_completion_percentage_organic']+'%</cite>');

				//goal value
				$('#goal-value-usersGoals').append('<span><cite>vs</cite> '+result['previous_goal_value']+ '</span>');
				if(result['goal_value_percentage'] < 0){
					var arrow2 = 'down'; var color2 = 'red';
				}else{
					var arrow2 = 'up'; var color2 = 'green';
				}
				$('.goal-value-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+result['goal_value_percentage']+'%</cite>');

				//goal value organic
				$('#goal-value-organicGoals').append('<span><cite>vs</cite> '+result['previous_goal_value_organic']+ '</span>');
				if(result['goal_value_percentage_organic'] < 0){
					var arrow3 = 'down'; var color3 = 'red';
				}else{
					var arrow3 = 'up'; var color3 = 'green';
				}
				$('.goal-value-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+result['goal_value_percentage_organic']+'%</cite>');
				
				//goal conversion rate users
				$('#goal-conversion-rate-usersGoals').append('<span><cite>vs</cite> '+result['previous_goal_conversion']+ '%</span>');
				if(result['goal_conversion_rate_percentage'] < 0){
					var arrow4 = 'down'; var color4 = 'red';
				}else{
					var arrow4 = 'up'; var color4 = 'green';
				}
				$('.goal-conversion-rate-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+result['goal_conversion_rate_percentage']+'%</cite>');

				//goal conversion rate organic
				$('#goal-conversion-rate-organicGoals').append('<span><cite>vs</cite> '+result['previous_goal_conversion_organic']+ '</span>');
				if(result['goal_conversion_rate_percentage_organic'] < 0){
					var arrow5 = 'down'; var color5 = 'red';
				}else{
					var arrow5 = 'up'; var color5 = 'green';
				}
				$('.goal-conversion-rate-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+result['goal_conversion_rate_percentage_organic']+'%</cite>');
				
				//goal Abandonment rate users
				$('#goal-abondon-rate-usersGoals').append('<span><cite>vs</cite> '+result['previous_goal_abondon']+ '</span>');
				if(result['goal_abondon_rate_percentage'] < 0){
					var arrow6 = 'down'; var color6 = 'red';
				}else{
					var arrow6 = 'up'; var color6 = 'green';
				}
				$('.goal-abondon-rate-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+result['goal_abondon_rate_percentage']+'%</cite>');

				//goal Abandonment rate organic
				$('#goal-abondon-rate-organicGoals').append('<span><cite>vs</cite> '+result['previous_goal_abondon_organic']+ '</span>');
				if(result['goal_abondon_rate_percentage_organic'] < 0){
					var arrow7 = 'down'; var color7 = 'red';
				}else{
					var arrow7 = 'up'; var color7 = 'green';
				}
				$('.goal-abondon-rate-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+result['goal_abondon_rate_percentage_organic']+'%</cite>');

			}else{
				$('.goals-chart-box').removeClass('goals-compare-section');
				$('.goal-completion-users-percentage').html('');
				$('.goal-completion-traffic-percentage').html('');
				$('.goal-value-users-percentage').html('');
				$('.goal-value-organic-percentage').html('');
				$('.goal-conversion-rate-users-percentage').html('');
				$('.goal-conversion-rate-organic-percentage').html('');
				$('.goal-abondon-rate-users-percentage').html('');
				$('.goal-abondon-rate-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.goal_completion_percentage').removeClass('ajax-loader');

		}
	});
}