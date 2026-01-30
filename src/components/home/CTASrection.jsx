import React from "react";
import { Link } from "react-router-dom";
import { createPageUrl } from "@/utils";
import { motion } from "framer-motion";
import { ArrowRight, Download, FileText, BookOpen } from "lucide-react";

export default function CTASection() {
  return (
    <section className="py-24 bg-gradient-to-br from-[#1e3a5f] to-[#2d5a8a] relative overflow-hidden">
      {/* Background Elements */}
      <div className="absolute inset-0">
        <div className="absolute top-0 right-0 w-96 h-96 bg-[#f5a623]/20 rounded-full blur-3xl" />
        <div className="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="grid lg:grid-cols-2 gap-12 items-center">
          {/* Left Content */}
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
          >
            <h2 className="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
              Join RETI Now and Take the{" "}
              <span className="text-[#f5a623]">First Step</span> Toward a Skilled Future
            </h2>
            <p className="text-xl text-gray-300 mb-8 leading-relaxed">
              Our practical, TEVETA accredited programs are designed to elevate your expertise and position you for meaningful career opportunities in Zambia's evolving industries.
            </p>
            <Link
              to={createPageUrl("Contact")}
              className="inline-flex items-center gap-2 bg-[#f5a623] hover:bg-[#e09515] text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-lg shadow-[#f5a623]/30 hover:shadow-xl group"
            >
              Register Now
              <ArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
            </Link>
          </motion.div>

          {/* Right - Download Cards */}
          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            className="space-y-6"
          >
            <h3 className="text-white font-semibold text-lg mb-4 flex items-center gap-2">
              <Download className="w-5 h-5 text-[#f5a623]" />
              Download Your Application Form and Course List
            </h3>

            <a
              href="https://reti-edu.web.app/downloads/RISING%20EAST%20TRAINING%20INSTITUTE%20APPLICATION%20FORM%202025-2026.pdf"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group"
            >
              <div className="w-16 h-16 bg-[#f5a623] rounded-2xl flex items-center justify-center flex-shrink-0">
                <FileText className="w-8 h-8 text-white" />
              </div>
              <div className="flex-grow">
                <h4 className="text-white font-semibold text-lg group-hover:text-[#f5a623] transition-colors">
                  Application Form
                </h4>
                <p className="text-gray-300 text-sm">
                  Download the 2025-2026 application form
                </p>
              </div>
              <Download className="w-6 h-6 text-white group-hover:text-[#f5a623] transition-colors" />
            </a>

            <a
              href="https://reti-edu.web.app/downloads/RETI_Courses_2025_2026.pdf"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-4 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 group"
            >
              <div className="w-16 h-16 bg-[#f5a623] rounded-2xl flex items-center justify-center flex-shrink-0">
                <BookOpen className="w-8 h-8 text-white" />
              </div>
              <div className="flex-grow">
                <h4 className="text-white font-semibold text-lg group-hover:text-[#f5a623] transition-colors">
                  Course List
                </h4>
                <p className="text-gray-300 text-sm">
                  View all available courses for 2025-2026
                </p>
              </div>
              <Download className="w-6 h-6 text-white group-hover:text-[#f5a623] transition-colors" />
            </a>
          </motion.div>
        </div>
      </div>
    </section>
  );
}